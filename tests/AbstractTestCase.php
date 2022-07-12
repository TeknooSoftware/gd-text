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

use function gd_info;
use function str_contains;

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
 */
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
        $sha1 = sha1($output = ob_get_contents());
        ob_end_clean();

        self::assertNotEmpty($output);
        if (str_contains((string) (gd_info()["GD Version"] ?? ''), '2.1')) {
            self::assertEquals(
                static::sha1ImageResource('2.1.0/' . $name),
                $sha1
            );
        } elseif (str_contains((string) (gd_info()["GD Version"] ?? ''), '2.3')) {
            self::assertEquals(
                static::sha1ImageResource('2.3.0/' . $name),
                $sha1
            );
        } else {
            self::markTestIncomplete('Not GD 2.1 version');
        }
    }
}
