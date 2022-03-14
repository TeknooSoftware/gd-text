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

use GDText\Box;
use GDText\Color;

/**
 * @copyright   Copyright (c) EIRL Richard Déloge (richarddeloge@gmail.com)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 * @copyright   Copyright (c) Pe46dro (https://github.com/Pe46dro/gd-text) [author of v1.x]
 * @copyright   Copyright (c) Stil (https://github.com/stil/gd-text) [author of v1.x]
 *
 * @link        http://teknoo.software/gd-text Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 *
 * @covers \GDText\Box
 */
class BoxTest extends AbstractTestCase
{
    public function testSetStrokeColor()
    {
        self::assertInstanceOf(
            Box::class,
            (new Box($this->openImageResource('owl_png24.png')))
                ->setStrokeColor(new Color(0, 0, 0))
        );
    }

    public function testSetStrokeSize()
    {
        self::assertInstanceOf(
            Box::class,
            (new Box($this->openImageResource('owl_png24.png')))
                ->setStrokeSize(10)
        );
    }

    public function testSetAngle()
    {
        self::assertInstanceOf(
            Box::class,
            (new Box($this->openImageResource('owl_png24.png')))
                ->setAngle(10)
        );
    }

    public function testSetTextShadow()
    {
        self::assertInstanceOf(
            Box::class,
            (new Box($this->openImageResource('owl_png24.png')))
                ->setTextShadow(new Color(0, 0, 0), 10, 10)
        );
    }

    public function testSetLineHeight()
    {
        self::assertInstanceOf(
            Box::class,
            (new Box($this->openImageResource('owl_png24.png')))
                ->setLineHeight(10)
        );
    }

    public function testSetBaseline()
    {
        self::assertInstanceOf(
            Box::class,
            (new Box($this->openImageResource('owl_png24.png')))
                ->setBaseline(10)
        );
    }

    public function testEnableDebug()
    {
        self::assertInstanceOf(
            Box::class,
            (new Box($this->openImageResource('owl_png24.png')))
                ->enableDebug()
        );
    }

    public function testDrawTextWithoutFont()
    {
        $this->expectException(\InvalidArgumentException::class);

        (new Box($this->openImageResource('owl_png24.png')))
            ->draw('foo');
    }
}