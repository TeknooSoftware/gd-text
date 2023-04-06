<?php

/*
 * GdText.
 *
 * LICENSE
 *
 * This source file is subject to the MIT license
 * it is available in LICENSE file at the root of this package
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to richard@teknoo.software so we can send you a copy immediately.
 *
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (richard@teknoo.software)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 *
 * @link        http://teknoo.software/imuutable Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */

declare(strict_types=1);

namespace GDText\Tests;

use GDText\Box;
use GDText\Color;

/**
 * @copyright   Copyright (c) EIRL Richard Déloge (richard@teknoo.software)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 * @copyright   Copyright (c) Pe46dro (https://github.com/Pe46dro/gd-text) [author of v1.x]
 * @copyright   Copyright (c) Stil (https://github.com/stil/gd-text) [author of v1.x]
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 *
 * @covers \GDText\Box
 * @covers \GDText\Enum\TextWrapping
 * @covers \GDText\Enum\HorizontalAlignment
 * @covers \GDText\Enum\VerticalAlignment
 */
class BoxTest extends AbstractTestCase
{
    public function testSetStrokeColor(): void
    {
        self::assertInstanceOf(
            Box::class,
            (new Box($this->openImageResource('owl_png24.png')))
                ->setStrokeColor(new Color(0, 0, 0))
        );
    }

    public function testSetStrokeSize(): void
    {
        self::assertInstanceOf(
            Box::class,
            (new Box($this->openImageResource('owl_png24.png')))
                ->setStrokeSize(10)
        );
    }

    public function testSetAngle(): void
    {
        self::assertInstanceOf(
            Box::class,
            (new Box($this->openImageResource('owl_png24.png')))
                ->setAngle(10)
        );
    }

    public function testSetTextShadow(): void
    {
        self::assertInstanceOf(
            Box::class,
            (new Box($this->openImageResource('owl_png24.png')))
                ->setTextShadow(new Color(0, 0, 0), 10, 10)
        );
    }

    public function testSetLineHeight(): void
    {
        self::assertInstanceOf(
            Box::class,
            (new Box($this->openImageResource('owl_png24.png')))
                ->setLineHeight(10)
        );
    }

    public function testSetBaseline(): void
    {
        self::assertInstanceOf(
            Box::class,
            (new Box($this->openImageResource('owl_png24.png')))
                ->setBaseline(10)
        );
    }

    public function testEnableDebug(): void
    {
        self::assertInstanceOf(
            Box::class,
            (new Box($this->openImageResource('owl_png24.png')))
                ->enableDebug()
        );
    }

    public function testDrawTextWithoutFont(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        (new Box($this->openImageResource('owl_png24.png')))
            ->draw('foo');
    }
}
