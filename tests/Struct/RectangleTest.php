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

namespace GDText\Tests\Struct;

use GDText\Struct\Rectangle;
use PHPUnit\Framework\TestCase;

/**
 * @copyright   Copyright (c) EIRL Richard Déloge (richarddeloge@gmail.com)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 *
 * @covers \GDText\Struct\Rectangle
 */
class RectangleTest extends TestCase
{
    private function buildRectangle(): Rectangle
    {
        return new Rectangle(10, 20, 35, 42);
    }

    public function testGetX(): void
    {
        self::assertEquals(
            10,
            $this->buildRectangle()->getX()
        );
    }

    public function testGetY(): void
    {
        self::assertEquals(
            20,
            $this->buildRectangle()->getY()
        );
    }

    public function testGetLeft(): void
    {
        self::assertEquals(
            10,
            $this->buildRectangle()->getLeft()
        );
    }

    public function testGetTop(): void
    {
        self::assertEquals(
            20,
            $this->buildRectangle()->getTop()
        );
    }

    public function testGetHeight(): void
    {
        self::assertEquals(
            42,
            $this->buildRectangle()->getHeight()
        );
    }

    public function testGetWidth(): void
    {
        self::assertEquals(
            35,
            $this->buildRectangle()->getWidth()
        );
    }

    public function testGetRight(): void
    {
        self::assertEquals(
            45,
            $this->buildRectangle()->getRight()
        );
    }

    public function testGetBottom(): void
    {
        self::assertEquals(
            62,
            $this->buildRectangle()->getBottom()
        );
    }
}
