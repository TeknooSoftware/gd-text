<?php

/*
 * GdText.
 *
 * LICENSE
 *
 * This source file is subject to the 3-Clause BSD license
 * it is available in LICENSE file at the root of this package
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to richard@teknoo.software so we can send you a copy immediately.
 *
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 * @copyright   Copyright (c) Pe46dro (https://github.com/Pe46dro/gd-text) [author of v1.x]
 * @copyright   Copyright (c) Stil (https://github.com/stil/gd-text) [author of v1.x]
 *
 * @link        https://teknoo.software/libraries/gd-text Project website
 *
 * @license     http://teknoo.software/license/bsd-3         3-Clause BSD License
 * @author      Richard Déloge <richard@teknoo.software>
 */

declare(strict_types=1);

namespace GDText;

use Exception;
use GdImage;
use GDText\Enum\HorizontalAlignment;
use GDText\Enum\TextWrapping;
use GDText\Enum\VerticalAlignment;
use GDText\Exception\NoBoxException;
use GDText\Struct\Point;
use GDText\Struct\Rectangle;
use InvalidArgumentException;

use function abs;
use function array_first;
use function ceil;
use function count;
use function explode;
use function imagefilledrectangle;
use function imagettfbbox;
use function imagettftext;
use function is_array;
use function is_iterable;
use function max;
use function min;
use function preg_split;
use function random_int;

/**
 * Central object to use to print text in an image. The text is localized in a box and will be adapted
 * (wrapped, aligned, oriented, etc...) according to box's configuration. An Box object need the GdImage resource
 * a property to be instantiated
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 * @copyright   Copyright (c) Pe46dro (https://github.com/Pe46dro/gd-text) [author of v1.x]
 * @copyright   Copyright (c) Stil (https://github.com/stil/gd-text) [author of v1.x]
 * @license     http://teknoo.software/license/bsd-3         3-Clause BSD License
 * @author      Richard Déloge <richard@teknoo.software>
 */
class Box
{
    private int $angle = 0;

    private int $strokeSize = 0;

    private Color $strokeColor;

    private int $fontSize = 12;

    private Color $fontColor;

    private HorizontalAlignment $alignX = HorizontalAlignment::Left;

    private VerticalAlignment $alignY = VerticalAlignment::Top;

    private TextWrapping $textWrapping = TextWrapping::WrapWithOverflow;

    private float $lineHeight = 1.25;

    private float $baseline = 0.2;

    private ?string $fontFace = null;

    private bool $debug = false;

    /**
     * @var array{color:Color, offset:Point}|null
     */
    private ?array $textShadow = null;

    private ?Color $backgroundColor = null;

    private Rectangle $box;

    public function __construct(
        private readonly GdImage $im,
    ) {
        $this->fontColor = new Color(0, 0, 0);
        $this->strokeColor = new Color(0, 0, 0);
        $this->box = new Rectangle(0, 0, 100, 100);
    }

    public function setFontColor(Color $color): self
    {
        $this->fontColor = $color;

        return $this;
    }

    public function setFontFace(string $path): self
    {
        $this->fontFace = $path;

        return $this;
    }

    public function setFontSize(int $v): self
    {
        if ($v < 1) {
            throw new InvalidArgumentException('Font size must be greater than or equal to 1.');
        }

        $this->fontSize = $v;

        return $this;
    }

    public function setStrokeColor(Color $color): self
    {
        $this->strokeColor = $color;

        return $this;
    }

    public function setStrokeSize(int $v): self
    {
        $this->strokeSize = $v;

        return $this;
    }

    public function setAngle(int $v): self
    {
        $this->angle = $v;

        return $this;
    }

    public function setTextShadow(Color $color, int $xShift, int $yShift): self
    {
        $this->textShadow = [
            'color'  => $color,
            'offset' => new Point($xShift, $yShift),
        ];

        return $this;
    }

    public function setBackgroundColor(Color $color): self
    {
        $this->backgroundColor = $color;

        return $this;
    }

    public function setLineHeight(float $v): self
    {
        if ($v <= 0) {
            throw new InvalidArgumentException('Line height must be greater than 0.');
        }

        $this->lineHeight = $v;

        return $this;
    }

    public function setBaseline(float $v): self
    {
        $this->baseline = $v;

        return $this;
    }

    public function setTextAlign(
        HorizontalAlignment $x = HorizontalAlignment::Left,
        VerticalAlignment $y = VerticalAlignment::Top
    ): self {

        $this->alignX = $x;
        $this->alignY = $y;

        return $this;
    }

    public function setBox(int $x, int $y, int $width, int $height): self
    {
        $this->box = new Rectangle($x, $y, $width, $height);

        return $this;
    }

    /**
     * Enables debug mode. Whole textbox and individual lines will be filled with random colors.
     */
    public function enableDebug(): self
    {
        $this->debug = true;

        return $this;
    }

    public function setTextWrapping(TextWrapping $textWrapping): self
    {
        $this->textWrapping = $textWrapping;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function draw(string $text): Rectangle
    {
        return $this->drawText($text, true);
    }

    /**
     * Draws the text on the picture, fitting it to the current box.
     *
     * The font size starts from the current font size (clamped between $minFontSize and $maxFontSize). If the text
     * fits in the box, the font size is increased step by step while the text still fits. Otherwise, it is decreased
     * step by step until the text fits. If the text never fits, it is drawn with the minimum font size (overflow).
     * The initial font size is restored after drawing.
     *
     * @param string $text Text to draw. May contain newline characters.
     * @param int $precision Increment or decrement of font size. The lower this value, the slower this method.
     *                       Must not be 0, its sign is ignored.
     * @param int $maxFontSize Maximum font size, or -1 for no limit.
     * @param int $minFontSize Minimum font size, or -1 for no limit (the font size is never lower than 1).
     * @param-out int $usedFontSize The font size used to draw the text.
     *
     * @return Rectangle Area that cover the drawn text
     * @throws Exception
     */
    public function drawFitFontSize(
        string $text,
        int $precision = -1,
        int $maxFontSize = -1,
        int $minFontSize = -1,
        ?int &$usedFontSize = null
    ): Rectangle {
        $precision = abs($precision);
        if (0 === $precision) {
            throw new InvalidArgumentException('Precision must not be 0.');
        }

        $initialFontSize = $this->fontSize;
        // A text that never fits must not loop forever: the font size is never lower than 1
        $floor = max(1, $minFontSize);
        $ceiling = $maxFontSize > 0 ? max($floor, $maxFontSize) : PHP_INT_MAX;
        $fontSize = min(max($initialFontSize, $floor), $ceiling);

        if ($this->fitsInBox($text, $fontSize)) {
            // Increment font size while the text still fits
            while ($ceiling - $fontSize >= $precision && $this->fitsInBox($text, $fontSize + $precision)) {
                $fontSize += $precision;
            }
        } else {
            // Decrement font size until the text fits, or the floor is reached
            while ($fontSize > $floor) {
                $fontSize = max($floor, $fontSize - $precision);

                if ($this->fitsInBox($text, $fontSize)) {
                    break;
                }
            }
        }

        $usedFontSize = $fontSize;
        $this->setFontSize($fontSize);

        try {
            return $this->drawText($text, true);
        } finally {
            // Restore initial font size
            $this->setFontSize($initialFontSize);
        }
    }

    /**
     * @throws Exception
     */
    private function fitsInBox(string $text, int $fontSize): bool
    {
        $this->setFontSize($fontSize);
        $rectangle = $this->calculate($text);

        return $rectangle->getWidth() <= $this->box->getWidth()
            && $rectangle->getHeight() <= $this->box->getHeight();
    }

    /**
     * Get the area that will cover the given text.
     * @throws Exception
     */
    #[\NoDiscard('calculate() only measures the text, the returned area is the result')]
    public function calculate(string $text): Rectangle
    {
        return $this->drawText($text, false);
    }

    /**
     * Draws the text on the picture.
     * @throws Exception
     */
    private function drawText(string $text, bool $draw): Rectangle
    {
        $fontFace = $this->fontFace;
        if (null === $fontFace) {
            throw new InvalidArgumentException('No path to font file has been specified.');
        }

        $lines = match ($this->textWrapping) {
            TextWrapping::NoWrap => [$text],
            TextWrapping::WrapWithOverflow => $this->wrapTextWithOverflow($text, $fontFace),
        };

        if ($draw && $this->debug) {
            // Marks whole texbox area with color
            $this->drawFilledRectangle(
                $this->box,
                new Color(
                    random_int(180, 255),
                    random_int(180, 255),
                    random_int(180, 255),
                    80
                )
            );
        }

        $lineHeightPx = (int) ceil($this->lineHeight * $this->fontSize);
        $textHeight = count($lines) * $lineHeightPx;

        $yAlign = (int) ceil(match ($this->alignY) {
            VerticalAlignment::Center => ($this->box->getHeight() / 2) - ($textHeight / 2),
            VerticalAlignment::Bottom => $this->box->getHeight() - $textHeight,
            VerticalAlignment::Top => 0,
        });

        $yShift = (int) ceil($lineHeightPx * (1 - $this->baseline));
        $boxX = $this->box->getX();
        $lineY = $this->box->getY() + $yAlign;

        // Resolve colors' indexes once per drawing, and not once per line, per stroke pixel, etc...
        $backgroundIndex = null;
        $backgroundShift = 0;
        $shadowIndex = null;
        $strokeIndex = null;
        $fontIndex = null;
        if ($draw) {
            if (null !== $this->backgroundColor) {
                $backgroundIndex = (int) $this->backgroundColor->getIndex($this->im);
                $backgroundShift = ($lineHeightPx - $this->fontSize)
                    + (int) ceil((1 - $this->lineHeight) * 13 * (1 / 50 * $this->fontSize));
            }

            if (null !== $this->textShadow) {
                $shadowIndex = (int) $this->textShadow['color']->getIndex($this->im);
            }

            if ($this->strokeSize > 0) {
                $strokeIndex = (int) $this->strokeColor->getIndex($this->im);
            }

            $fontIndex = (int) $this->fontColor->getIndex($this->im);
        }

        $drawnX = PHP_INT_MAX;
        $drawnY = PHP_INT_MAX;
        $drawnH = 0;
        $drawnW = 0;

        foreach ($lines as $line) {
            $box = $this->calculateBox($line, $fontFace);
            $lineWidth = $box->getWidth();
            $xAlign = (int) ceil(match ($this->alignX) {
                HorizontalAlignment::Center => ($this->box->getWidth() - $lineWidth) / 2,
                HorizontalAlignment::Right => $this->box->getWidth() - $lineWidth,
                HorizontalAlignment::Left => 0,
            });

            // current line X and Y position
            $xMOD = $boxX + $xAlign;
            $yMOD = $lineY + $yShift;

            if (null !== $backgroundIndex && '' !== $line) {
                // Marks whole texbox area with given background-color
                $this->fillRectangle(
                    new Rectangle($xMOD, $lineY + $backgroundShift, $lineWidth, $this->fontSize),
                    $backgroundIndex
                );
            }

            if ($draw && $this->debug) {
                // Marks current line with color
                $this->drawFilledRectangle(
                    new Rectangle($xMOD, $lineY, $lineWidth, $lineHeightPx),
                    new Color(
                        random_int(1, 180),
                        random_int(1, 180),
                        random_int(1, 180),
                    )
                );
            }

            if (null !== $fontIndex) {
                if (null !== $shadowIndex && null !== $this->textShadow) {
                    $this->drawInternal(
                        $xMOD + $this->textShadow['offset']->getX(),
                        $yMOD + $this->textShadow['offset']->getY(),
                        $shadowIndex,
                        $line,
                        $fontFace,
                    );
                }

                if (null !== $strokeIndex) {
                    $this->strokeText($xMOD, $yMOD, $strokeIndex, $line, $fontFace);
                }

                $this->drawInternal($xMOD, $yMOD, $fontIndex, $line, $fontFace);
            }

            $drawnX = min($xMOD, $drawnX);
            $drawnY = min($lineY, $drawnY);
            $drawnW = max($drawnW, $lineWidth);
            $drawnH += $lineHeightPx;

            $lineY += $lineHeightPx;
        }

        return new Rectangle($drawnX, $drawnY, $drawnW, $drawnH);
    }

    /**
     * Splits overflowing text into array of strings.
     *
     * @return string[]
     */
    private function wrapTextWithOverflow(string $text, string $fontFace): array
    {
        $lines = [];
        // Split text explicitly into lines by \n, \r\n and \r
        $explicitLines = preg_split('#\n|\r\n?#', $text);

        // @codeCoverageIgnoreStart
        if (!is_iterable($explicitLines)) {
            return [$text];
        }

        // @codeCoverageIgnoreEnd

        foreach ($explicitLines as $line) {
            // Check every line if it needs to be wrapped
            $words = explode(' ', $line);
            $line = array_first($words);
            $countOfWords = count($words);

            for ($i = 1; $i < $countOfWords; ++$i) {
                $box = $this->calculateBox($line . ' ' . $words[$i], $fontFace);
                if ($box->getWidth() >= $this->box->getWidth()) {
                    $lines[] = $line;
                    $line = $words[$i];
                } else {
                    $line .= ' ' . $words[$i];
                }
            }

            $lines[] = $line;
        }

        return $lines;
    }

    private function getFontSizeInPoints(): float
    {
        return 0.75 * $this->fontSize;
    }

    private function drawFilledRectangle(Rectangle $rect, Color $color): void
    {
        $this->fillRectangle($rect, (int) $color->getIndex($this->im));
    }

    private function fillRectangle(Rectangle $rect, int $colorIndex): void
    {
        imagefilledrectangle(
            $this->im,
            $rect->getLeft(),
            $rect->getTop(),
            $rect->getRight(),
            $rect->getBottom(),
            $colorIndex
        );
    }

    /**
     * Returns the bounding box of a text.
     */
    private function calculateBox(string $text, string $fontFace): Rectangle
    {
        $borders = imagettfbbox(
            $this->getFontSizeInPoints(),
            0,
            $fontFace,
            $text
        );

        // @codeCoverageIgnoreStart
        if (!is_array($borders)) {
            throw new NoBoxException('Error in imagettfbbox process, no box generated');
        }

        // @codeCoverageIgnoreEnd

        /** @var array{int, int, int, int, int, int, int, int, int, int} $borders */
        [$xLeft, $yLower, $xRight,,, $yUpper] = $borders;

        return new Rectangle(
            $xLeft,
            $yUpper,
            $xRight - $xLeft,
            $yLower - $yUpper
        );
    }

    private function strokeText(int $x, int $y, int $colorIndex, string $text, string $fontFace): void
    {
        $size = $this->strokeSize;
        if ($size <= 0) {
            return;
        }

        for ($c1 = $x - $size; $c1 <= $x + $size; ++$c1) {
            for ($c2 = $y - $size; $c2 <= $y + $size; ++$c2) {
                $this->drawInternal($c1, $c2, $colorIndex, $text, $fontFace);
            }
        }
    }

    private function drawInternal(
        int $x,
        int $y,
        int $colorIndex,
        string $text,
        string $fontFace
    ): void {
        imagettftext(
            $this->im,
            $this->getFontSizeInPoints(),
            $this->angle,
            $x,
            $y,
            $colorIndex,
            $fontFace,
            $text
        );
    }
}
