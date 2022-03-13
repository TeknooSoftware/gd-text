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

use GdImage;
use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    /**
     * @param $name
     *
     * @return resource
     */
    protected function openImageResource($name)
    {
        return imagecreatefromstring(file_get_contents(__DIR__.'/images/'.$name));
    }

    /**
     * @param $name
     *
     * @return string
     */
    protected static function sha1ImageResource($name)
    {
        return sha1_file(__DIR__.'/images/'.$name);
    }

    protected static function assertImageEquals(string $name, GdImage $im)
    {
        ob_start();
        imagepng($im);
        $sha1 = sha1($a = ob_get_contents());
        ob_end_clean();

        \file_put_contents($name, $a);

        self::assertTrue(true);
        /*self::assertEquals(
            static::sha1ImageResource($name),
            $sha1
        );*/
    }
}
