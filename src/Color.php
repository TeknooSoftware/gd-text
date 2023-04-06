<?php

/*
 * GdText.
 *
 * LICENSE
 *
 * This source file is subject to the MIT license
 * that are bundled with this package in the folder licences
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to richard@teknoo.software so we can send you a copy immediately.
 *
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (richard@teknoo.software)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 * @copyright   Copyright (c) Pe46dro (https://github.com/Pe46dro/gd-text) [author of v1.x]
 * @copyright   Copyright (c) Stil (https://github.com/stil/gd-text) [author of v1.x]
 *
 * @link        http://teknoo.software/gd-text Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */

declare(strict_types=1);

namespace GDText;

use GdImage;
use InvalidArgumentException;

use function hexdec;
use function imagecolorallocate;
use function imagecolorallocatealpha;
use function imagecolorexact;
use function imagecolorexactalpha;
use function str_repeat;
use function str_replace;
use function strlen;
use function substr;

/**
 * 8-bit RGB color representation.
 * Provide also class's methods conversion from HSL format or HTML notation
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (richard@teknoo.software)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 * @copyright   Copyright (c) Pe46dro (https://github.com/Pe46dro/gd-text) [author of v1.x]
 * @copyright   Copyright (c) Stil (https://github.com/stil/gd-text) [author of v1.x]
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */
class Color
{
    /**
     * @param int $red   Value of red component 0-255
     * @param int $green Value of green component 0-255
     * @param int $blue  Value of blue component 0-255
     * @param ?int $alpha A value between 0 and 127. 0 indicates completely opaque while 127 indicates completely
     *                   transparent.
     */
    public function __construct(
        private readonly int $red = 0,
        private readonly int $green = 0,
        private readonly int $blue = 0,
        private readonly ?int $alpha = null,
    ) {
    }

    /**
     * Parses string to Color object representation.
     *
     * @param string $str String with color information, ex. #000000
     */
    public static function parseString(string $str): self
    {
        $str = str_replace('#', '', $str);
        if (6 === strlen($str)) {
            $r = (int) hexdec(substr($str, 0, 2));
            $g = (int) hexdec(substr($str, 2, 2));
            $b = (int) hexdec(substr($str, 4, 2));
        } elseif (3 === strlen($str)) {
            $r = (int) hexdec(str_repeat($str[0], 2));
            $g = (int) hexdec(str_repeat($str[1], 2));
            $b = (int) hexdec(str_repeat($str[2], 2));
        } else {
            throw new InvalidArgumentException('Unrecognized color.');
        }

        return new self($r, $g, $b);
    }

    public static function fromHsl(float $h, float $s, float $l): self
    {
        $fromFloat = static function (array $rgb): Color {
            foreach ($rgb as &$v) {
                $v = (int) round($v * 255);
            }

            return new self($rgb[0], $rgb[1], $rgb[2]);
        };

        // If saturation is 0, the given color is grey and only
        // lightness is relevant.
        if (0.0 === $s) {
            return $fromFloat([$l, $l, $l]);
        }

        // Else calculate r, g, b according to hue.
        // Check http://en.wikipedia.org/wiki/HSL_and_HSV#From_HSL for details
        $chroma = (1 - abs(2 * $l - 1)) * $s;
        $h2 = $h * 6;
        $x = $chroma * (1 - abs((fmod($h2, 2)) - 1)); // Note: fmod because % (modulo) returns int value!!
        $m = $l - round($chroma / 2, 10); // Bugfix for strange float behaviour (e.g. $l=0.17 and $s=1)

        $rgb = match (true) {
            ($h2 >= 0 && $h2 < 1) => [($chroma + $m), ($x + $m), $m],
            ($h2 >= 1 && $h2 < 2) => [($x + $m), ($chroma + $m), $m],
            ($h2 >= 2 && $h2 < 3) => [$m, ($chroma + $m), ($x + $m)],
            ($h2 >= 3 && $h2 < 4) => [$m, ($x + $m), ($chroma + $m)],
            ($h2 >= 4 && $h2 < 5) => [($x + $m), $m, ($chroma + $m)],
            ($h2 >= 5 && $h2 < 6) => [($chroma + $m), $m, ($x + $m)],
            default => throw new InvalidArgumentException('Invalid hue, it should be a value between 0 and 1.'),
        };

        return $fromFloat($rgb);
    }

    /**
     * @return int|false Returns the index of the specified color+alpha in the palette of the image,
     *             or index of allocated color if the color does not exist in the image's palette.
     */
    public function getIndex(GdImage $image): int|false
    {
        if ($this->hasAlphaChannel()) {
            $index = imagecolorexactalpha(
                $image,
                $this->red,
                $this->green,
                $this->blue,
                (int) $this->alpha
            );
        } else {
            $index = imagecolorexact(
                $image,
                $this->red,
                $this->green,
                $this->blue
            );
        }

        if (-1 !== $index) {
            return $index;
        }

        if ($this->hasAlphaChannel()) {
            return imagecolorallocatealpha(
                $image,
                $this->red,
                $this->green,
                $this->blue,
                (int) $this->alpha
            );
        }

        return imagecolorallocate(
            $image,
            $this->red,
            $this->green,
            $this->blue
        );
    }

    public function hasAlphaChannel(): bool
    {
        return null !== $this->alpha;
    }

    /**
     * @return int[]
     */
    public function toArray(): array
    {
        return [$this->red, $this->green, $this->blue];
    }
}
