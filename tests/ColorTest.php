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
}
