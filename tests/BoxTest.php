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
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 *
 * @link        http://teknoo.software/imuutable Project website
 *
 * @license     https://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */

declare(strict_types=1);

namespace GDText\Tests;

use GDText\Box;
use GDText\Color;
use GDText\Enum\HorizontalAlignment;
use GDText\Enum\TextWrapping;
use GDText\Enum\VerticalAlignment;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 * @copyright   Copyright (c) Pe46dro (https://github.com/Pe46dro/gd-text) [author of v1.x]
 * @copyright   Copyright (c) Stil (https://github.com/stil/gd-text) [author of v1.x]
 * @license     https://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 *
 */
#[CoversClass(VerticalAlignment::class)]
#[CoversClass(HorizontalAlignment::class)]
#[CoversClass(TextWrapping::class)]
#[CoversClass(Box::class)]
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
