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

use GdImage;
use PHPUnit\Framework\TestCase;

use function gd_info;
use function str_contains;

/**
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 * @copyright   Copyright (c) Pe46dro (https://github.com/Pe46dro/gd-text) [author of v1.x]
 * @copyright   Copyright (c) Stil (https://github.com/stil/gd-text) [author of v1.x]
 * @license     https://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */
abstract class AbstractTestCase extends TestCase
{
    /**
     * @param $name
     *
     * @return resource
     */
    protected function openImageResource($name): \GdImage|bool
    {
        return imagecreatefromstring(file_get_contents(__DIR__.'/images/'.$name));
    }

    /**
     * @param $name
     *
     * @return string
     */
    protected static function sha256ImageResource($name)
    {
        return hash_file('sha256', __DIR__.'/images/'.$name);
    }

    protected static function assertImageEquals(string $name, GdImage $im)
    {
        ob_start();
        imagepng($im);
        $sha = hash('sha256', $output = ob_get_contents());
        ob_end_clean();

        self::assertNotEmpty($output);
        if (str_contains((string)(gd_info()["GD Version"] ?? ''), '2.1')) {
            self::assertEquals(
                static::sha256ImageResource('2.1.0/' . $name),
                $sha
            );
        } elseif (str_contains((string)(gd_info()["GD Version"] ?? ''), '2.3.3')) {
            self::assertEquals(
                static::sha256ImageResource('2.3.3/' . $name),
                $sha
            );
        } elseif (str_contains((string)(gd_info()["GD Version"] ?? ''), '2.3')) {
            self::assertEquals(
                static::sha256ImageResource('2.3.0/' . $name),
                $sha
            );
        } else {
            self::markTestIncomplete('Not GD 2.1 version');
        }
    }
}
