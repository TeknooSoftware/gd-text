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

namespace GDText\Tests\Struct;

use GDText\Struct\Point;
use PHPUnit\Framework\TestCase;

/**
 * @copyright   Copyright (c) EIRL Richard Déloge (richard@teknoo.software)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 *
 * @covers \GDText\Struct\Point
 */
class PointTest extends TestCase
{
    private function buildPoint(): Point
    {
        return new Point(10, 20);
    }

    public function testGetX(): void
    {
        self::assertEquals(
            10,
            $this->buildPoint()->getX()
        );
    }

    public function testGetY(): void
    {
        self::assertEquals(
            20,
            $this->buildPoint()->getY()
        );
    }
}
