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
 *
 * @link        http://teknoo.software/imuutable Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */

declare(strict_types=1);

namespace GDText\Tests;

use GDText\Color;
use InvalidArgumentException;

/**
 * @covers \GDText\Color
 */
class ColorTest extends AbstractTestCase
{
    public function testPaletteImage()
    {
        $im = $this->openImageResource('owl.gif');

        $color = new Color(0, 0, 255);

        $index = $color->getIndex($im);
        self::assertNotEquals(-1, $index);
    }

    public function testPaletteImageWithAlpha()
    {
        $im = $this->openImageResource('owl.gif');

        $color = new Color(0, 0, 255, 50);

        $index = $color->getIndex($im);
        self::assertNotEquals(-1, $index);
    }

    public function testTrueColorImage()
    {
        $im = $this->openImageResource('owl_png24.png');

        $color = new Color(0, 0, 255);

        $index = $color->getIndex($im);
        self::assertNotEquals(-1, $index);

        $im = imagecreatetruecolor(1, 1);

        $index = $color->getIndex($im);
        self::assertNotEquals(-1, $index);
    }

    public function testTrueColorImageWithAlpha()
    {
        $im = $this->openImageResource('owl_png24.png');

        $color = new Color(0, 0, 255, 50);

        $index = $color->getIndex($im);
        self::assertNotEquals(-1, $index);

        $im = imagecreatetruecolor(1, 1);

        $index = $color->getIndex($im);
        self::assertNotEquals(-1, $index);
    }

    public function testToArray()
    {
        $color = new Color(12, 34, 56);
        self::assertEquals([12, 34, 56], $color->toArray());
    }

    public function testFromHsl()
    {
        $table = [
            [[0.5, 0.8, 0.3], [15, 138, 138]],
            [[0.999, 1, 1], [255, 255, 255]],
            [[0, 0, 0], [0, 0, 0]],
            [[338 / 360, 0.85, 0.25], [118, 10, 49]],
        ];

        foreach ($table as $pair) {
            list($hsl, $rgb) = $pair;
            $color = Color::fromHsl($hsl[0], $hsl[1], $hsl[2]);

            self::assertEquals($rgb, $color->toArray());
        }
    }

    public function testFromHslWithError()
    {
        $table = [
            [[0.5, 0.8, 0.3], [15, 138, 138]],
            [[0.999, 1, 1], [255, 255, 255]],
            [[0, 0, 0], [0, 0, 0]],
            [[338 / 360, 0.85, 0.25], [118, 10, 49]],
        ];

        foreach ($table as $pair) {
            list($hsl, $rgb) = $pair;
            $color = Color::fromHsl($hsl[0], $hsl[1], $hsl[2]);

            self::assertEquals($rgb, $color->toArray());
        }

        $this->expectException(InvalidArgumentException::class);
        Color::fromHsl(500, 400, 300);
    }

    public function testParseString()
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
            self::assertEquals($pair[1], $color->toArray());
        }
    }

    public function testParseStringInvalide()
    {
        $this->expectException(InvalidArgumentException::class);
        Color::parseString('oooooopp');
    }
}
