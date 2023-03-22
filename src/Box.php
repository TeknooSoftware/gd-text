<?php

/*
 * GdText.
 *
 * LICENSE
 *
 * This source file is subject to the MIT license
 * license that are bundled with this package in the folder licences
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to richarddeloge@gmail.com so we can send you a copy immediately.
 *
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (richarddeloge@gmail.com)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 * @copyright   Copyright (c) Pe46dro (https://github.com/Pe46dro/gd-text) [author of v1.x]
 * @copyright   Copyright (c) Stil (https://github.com/stil/gd-text) [author of v1.x]
 *
 * @link        http://teknoo.software/gd-text Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
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
use RuntimeException;

use function abs;
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
 * @copyright   Copyright (c) EIRL Richard Déloge (richarddeloge@gmail.com)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 * @copyright   Copyright (c) Pe46dro (https://github.com/Pe46dro/gd-text) [author of v1.x]
 * @copyright   Copyright (c) Stil (https://github.com/stil/gd-text) [author of v1.x]
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
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
     * @param string $text Text to draw. May contain newline characters.
     * @param int $precision Increment or decrement of font size. The lower this value, the slower this method.
     *
     * @return Rectangle Area that cover the drawn text
     * @throws Exception
     */
    public function drawFitFontSize(
        string $text,
        int $precision = -1,
        int $maxFontSize = -1,
        int $minFontSize = -1,
        int &$usedFontSize = null
    ): Rectangle {
        $initialFontSize = $this->fontSize;

        $usedFontSize = $this->fontSize;
        $rectangle = $this->calculate($text);

        $precision = (int) abs($precision);

        if ($rectangle->getHeight() > $this->box->getHeight() || $rectangle->getWidth() > $this->box->getWidth()) {
            // Decrement font size
            do {
                $this->setFontSize($usedFontSize);
                $rectangle = $this->calculate($text);

                $usedFontSize -= $precision;
            } while (
                (
                    -1 === $minFontSize
                    || $usedFontSize > $minFontSize
                )
                && (
                    $rectangle->getHeight() > $this->box->getHeight()
                    || $rectangle->getWidth() > $this->box->getWidth()
                )
            );

            $usedFontSize += $precision;
        } else {
            // Increment font size
            do {
                $this->setFontSize($usedFontSize);
                $rectangle = $this->calculate($text);

                $usedFontSize += $precision;
            } while (
                (
                    $maxFontSize > 0
                    && $usedFontSize < $maxFontSize
                )
                && $rectangle->getHeight() < $this->box->getHeight()
                && $rectangle->getWidth() < $this->box->getWidth()
            );

            $usedFontSize -= $precision * 2;
        }

        $this->setFontSize($usedFontSize);

        $rectangle = $this->drawText($text, true);

        // Restore initial font size
        $this->setFontSize($initialFontSize);

        return $rectangle;
    }

    /**
     * Get the area that will cover the given text.
     * @throws Exception
     */
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
        if (null === $this->fontFace) {
            throw new InvalidArgumentException('No path to font file has been specified.');
        }

        $lines = match ($this->textWrapping) {
            TextWrapping::NoWrap => [$text],
            TextWrapping::WrapWithOverflow => $this->wrapTextWithOverflow($text, $this->fontFace),
        };

        if ($this->debug) {
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

        $n = 0;
        $drawnX = PHP_INT_MAX;
        $drawnY = PHP_INT_MAX;
        $drawnH = 0;
        $drawnW = 0;

        foreach ($lines as $line) {
            $box = $this->calculateBox($line, (string) $this->fontFace);
            $xAlign = (int) ceil(match ($this->alignX) {
                HorizontalAlignment::Center => ($this->box->getWidth() - $box->getWidth()) / 2,
                HorizontalAlignment::Right => $this->box->getWidth() - $box->getWidth(),
                HorizontalAlignment::Left => 0,
            });

            $yShift = (int) ceil($lineHeightPx * (1 - $this->baseline));

            // current line X and Y position
            $xMOD = $this->box->getX() + $xAlign;
            $yMOD = $this->box->getY() + $yAlign + $yShift + ($n * $lineHeightPx);

            if ($draw && !empty($line) && null !== $this->backgroundColor) {
                // Marks whole texbox area with given background-color
                $backgroundHeight = $this->fontSize;

                $this->drawFilledRectangle(
                    new Rectangle(
                        $xMOD,
                        $this->box->getY()
                            + $yAlign
                            + ($n * $lineHeightPx)
                            + ($lineHeightPx - $backgroundHeight)
                            + (int) ceil((1 - $this->lineHeight) * 13 * (1 / 50 * $this->fontSize)),
                        $box->getWidth(),
                        $backgroundHeight
                    ),
                    $this->backgroundColor
                );
            }

            if ($this->debug) {
                // Marks current line with color
                $this->drawFilledRectangle(
                    new Rectangle(
                        $xMOD,
                        $this->box->getY() + $yAlign + ($n * $lineHeightPx),
                        $box->getWidth(),
                        $lineHeightPx
                    ),
                    new Color(
                        random_int(1, 180),
                        random_int(1, 180),
                        random_int(1, 180),
                    )
                );
            }

            if ($draw) {
                if (null !== $this->textShadow) {
                    $this->drawInternal(
                        new Point(
                            $xMOD + $this->textShadow['offset']->getX(),
                            $yMOD + $this->textShadow['offset']->getY()
                        ),
                        $this->textShadow['color'],
                        $line,
                        (string) $this->fontFace,
                    );
                }

                $this->strokeText($xMOD, $yMOD, $line, (string) $this->fontFace);
                $this->drawInternal(
                    new Point(
                        $xMOD,
                        $yMOD
                    ),
                    $this->fontColor,
                    $line,
                    (string) $this->fontFace,
                );
            }

            $drawnX = min($xMOD, $drawnX);
            $drawnY = min($this->box->getY() + $yAlign + ($n * $lineHeightPx), $drawnY);
            $drawnW = max($drawnW, $box->getWidth());
            $drawnH += $lineHeightPx;

            ++$n;
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
            $line = $words[0];
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
        imagefilledrectangle(
            $this->im,
            $rect->getLeft(),
            $rect->getTop(),
            $rect->getRight(),
            $rect->getBottom(),
            (int) $color->getIndex($this->im)
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

        [$xLeft, $yLower, $xRight,,, $yUpper] = $borders;

        return new Rectangle(
            $xLeft,
            $yUpper,
            $xRight - $xLeft,
            $yLower - $yUpper
        );
    }

    private function strokeText(int $x, int $y, string $text, string $fontFace): void
    {
        $size = $this->strokeSize;
        if ($size <= 0) {
            return;
        }

        for ($c1 = $x - $size; $c1 <= $x + $size; ++$c1) {
            for ($c2 = $y - $size; $c2 <= $y + $size; ++$c2) {
                $this->drawInternal(new Point($c1, $c2), $this->strokeColor, $text, $fontFace);
            }
        }
    }

    private function drawInternal(
        Point $position,
        Color $color,
        string $text,
        string $fontFace
    ): void {
        imagettftext(
            $this->im,
            $this->getFontSizeInPoints(),
            $this->angle,
            $position->getX(),
            $position->getY(),
            (int) $color->getIndex($this->im),
            $fontFace,
            $text
        );
    }
}
