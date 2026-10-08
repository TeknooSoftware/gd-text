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
 *
 * @link        http://teknoo.software/imuutable Project website
 *
 * @license     http://teknoo.software/license/bsd-3         3-Clause BSD License
 * @author      Richard Déloge <richard@teknoo.software>
 */

declare(strict_types=1);

namespace GDText\Tests;

use GDText\Color;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 * @copyright   Copyright (c) Pe46dro (https://github.com/Pe46dro/gd-text) [author of v1.x]
 * @copyright   Copyright (c) Stil (https://github.com/stil/gd-text) [author of v1.x]
 * @license     http://teknoo.software/license/bsd-3         3-Clause BSD License
 * @author      Richard Déloge <richard@teknoo.software>
 *
 */
#[CoversClass(Color::class)]
class ColorTest extends AbstractTestCase
{
    public function testConstructorWithBadColorRed()
    {
        $this->expectException(InvalidArgumentException::class);
        new Color(256, 0, 0);
    }

    public function testConstructorWithBadColorGreen()
    {
        $this->expectException(InvalidArgumentException::class);
        new Color(0, 256, 0);
    }

    public function testConstructorWithBadColorBlue()
    {
        $this->expectException(InvalidArgumentException::class);
        new Color(0, 0, 256);
    }

    public function testConstructorWithBadAlpha()
    {
        $this->expectException(InvalidArgumentException::class);
        new Color(0, 0, 0, 128);
    }

    public function testConstructorWithNegativeColorRed()
    {
        $this->expectException(InvalidArgumentException::class);
        new Color(-1, 0, 0);
    }

    public function testConstructorWithNegativeColorGreen()
    {
        $this->expectException(InvalidArgumentException::class);
        new Color(0, -1, 0);
    }

    public function testConstructorWithNegativeColorBlue()
    {
        $this->expectException(InvalidArgumentException::class);
        new Color(0, 0, -1);
    }

    public function testConstructorWithNegativeAlpha()
    {
        $this->expectException(InvalidArgumentException::class);
        new Color(0, 0, 0, -1);
    }

    public function testPaletteImage(): void
    {
        $im = $this->openImageResource('owl.gif');

        $color = new Color(0, 0, 255);

        $index = $color->getIndex($im);
        $this->assertNotSame(-1, $index);
    }

    public function testPaletteImageWithAlpha(): void
    {
        $im = $this->openImageResource('owl.gif');

        $color = new Color(0, 0, 255, 50);

        $index = $color->getIndex($im);
        $this->assertNotSame(-1, $index);
    }

    public function testTrueColorImage(): void
    {
        $im = $this->openImageResource('owl_png24.png');

        $color = new Color(0, 0, 255);

        $index = $color->getIndex($im);
        $this->assertNotSame(-1, $index);

        $im = imagecreatetruecolor(1, 1);

        $index = $color->getIndex($im);
        $this->assertNotSame(-1, $index);
    }

    public function testTrueColorImageWithAlpha(): void
    {
        $im = $this->openImageResource('owl_png24.png');

        $color = new Color(0, 0, 255, 50);

        $index = $color->getIndex($im);
        $this->assertNotSame(-1, $index);

        $im = imagecreatetruecolor(1, 1);

        $index = $color->getIndex($im);
        $this->assertNotSame(-1, $index);
    }

    public function testToArray(): void
    {
        $color = new Color(12, 34, 56);
        $this->assertSame([12, 34, 56], $color->toArray());
    }

    public function testFromHsl(): void
    {
        $table = [
            [[0.5, 0.8, 0.3], [15, 138, 138]],
            [[0.999, 1, 1], [255, 255, 255]],
            [[0, 0, 0], [0, 0, 0]],
            [[338 / 360, 0.85, 0.25], [118, 10, 49]],
        ];

        foreach ($table as $pair) {
            [$hsl, $rgb] = $pair;
            $color = Color::fromHsl($hsl[0], $hsl[1], $hsl[2]);

            $this->assertEquals($rgb, $color->toArray());
        }
    }

    public function testFromHslWithError(): void
    {
        $table = [
            [[0.5, 0.8, 0.3], [15, 138, 138]],
            [[0.999, 1, 1], [255, 255, 255]],
            [[0, 0, 0], [0, 0, 0]],
            [[338 / 360, 0.85, 0.25], [118, 10, 49]],
        ];

        foreach ($table as $pair) {
            [$hsl, $rgb] = $pair;
            $color = Color::fromHsl($hsl[0], $hsl[1], $hsl[2]);

            $this->assertEquals($rgb, $color->toArray());
        }

        $this->expectException(InvalidArgumentException::class);
        Color::fromHsl(500, 400, 300);
    }

    public function testParseString(): void
    {
        $table = [
            ['#000', [0, 0, 0]],
            ['#fff', [255, 255, 255]],
            ['#abcdef', [171, 205, 239]],
            ['#FEDCBA', [254, 220, 186]],
            ['FEDCBA', [254, 220, 186]],
            ['#abc', [170, 187, 204]],
            ['abc', [170, 187, 204]],
        ];

        foreach ($table as $pair) {
            $color = Color::parseString($pair[0]);
            $this->assertEquals($pair[1], $color->toArray());
        }
    }

    public function testParseStringInvalide(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Color::parseString('oooooopp');
    }

    public static function provideInvalidColorStrings(): array
    {
        return [
            'non hex 6 chars' => ['zzzzzz'],
            'non hex 3 chars with hash' => ['#GGG'],
            'hash in the middle' => ['#ab#c'],
            'double hash' => ['##abc'],
            'empty string' => [''],
            'only hash' => ['#'],
            'too short' => ['#ab'],
            'too long' => ['#abcdefa'],
            'spaces' => [' abc '],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('provideInvalidColorStrings')]
    public function testParseStringRejectsNonHexadecimalStrings(string $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        Color::parseString($value);
    }

    public function testFromHslWithHueOneIsSameAsHueZero(): void
    {
        $this->assertSame(
            Color::fromHsl(0.0, 0.5, 0.5)->toArray(),
            Color::fromHsl(1.0, 0.5, 0.5)->toArray(),
        );
        $this->assertSame([255, 0, 0], Color::fromHsl(1.0, 1.0, 0.5)->toArray());
        $this->assertSame([128, 128, 128], Color::fromHsl(1.0, 0.0, 0.5)->toArray());
    }

    public static function provideInvalidHsl(): array
    {
        return [
            'hue above 1' => [1.5, 0.5, 0.5, 'hue'],
            'negative hue' => [-0.1, 0.5, 0.5, 'hue'],
            'saturation above 1' => [0.5, 2.0, 0.5, 'saturation'],
            'negative saturation' => [0.5, -0.5, 0.5, 'saturation'],
            'lightness above 1' => [0.5, 0.5, 1.5, 'lightness'],
            'negative lightness' => [0.5, 0.5, -1.0, 'lightness'],
            'grey with lightness above 1' => [0.5, 0.0, 1.5, 'lightness'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('provideInvalidHsl')]
    public function testFromHslRejectsOutOfRangeComponents(float $h, float $s, float $l, string $component): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/' . $component . '/i');
        Color::fromHsl($h, $s, $l);
    }

    private function buildFullPaletteImage(): \GdImage
    {
        $im = imagecreate(16, 16);
        for ($i = 0; $i < 256; ++$i) {
            imagecolorallocate($im, $i, 0, 0);
        }

        return $im;
    }

    public function testFullPaletteImageReturnsClosestColorIndex(): void
    {
        $im = $this->buildFullPaletteImage();

        $index = (new Color(200, 0, 0))->getIndex($im);
        $this->assertIsInt($index);
        $this->assertSame(['red' => 200, 'green' => 0, 'blue' => 0, 'alpha' => 0], imagecolorsforindex($im, $index));

        $index = (new Color(0, 255, 0))->getIndex($im);
        $this->assertIsInt($index);
        $this->assertGreaterThanOrEqual(0, $index);
    }

    public function testFullPaletteImageWithAlphaReturnsClosestColorIndex(): void
    {
        $im = $this->buildFullPaletteImage();

        $index = (new Color(0, 255, 0, 50))->getIndex($im);
        $this->assertIsInt($index);
        $this->assertGreaterThanOrEqual(0, $index);
    }

    public static function provideHueSectors(): array
    {
        // Expected values computed independently with Python's colorsys.hls_to_rgb(h, 0.5, 0.6)
        return [
            'sector 0 (red to yellow)' => [0.05, [204, 97, 51]],
            'sector 1 (yellow to green)' => [0.25, [128, 204, 51]],
            'sector 2 (green to cyan)' => [0.4, [51, 204, 112]],
            'sector 3 (cyan to blue)' => [0.55, [51, 158, 204]],
            'sector 4 (blue to magenta)' => [0.7, [82, 51, 204]],
            'sector 5 (magenta to red)' => [0.9, [204, 51, 143]],
            'sector 5 middle' => [5.5 / 6, [204, 51, 128]],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('provideHueSectors')]
    public function testFromHslCoversAllHueSectors(float $hue, array $expected): void
    {
        $this->assertSame($expected, Color::fromHsl($hue, 0.6, 0.5)->toArray());
    }
}
