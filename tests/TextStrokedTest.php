<?php

/*
 * GdText.
 *
 * LICENSE
 *
 * This source file is subject to the MIT license
 * that are bundled with this package in the folder licences
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
use GDText\Enum\HorizontalAlignment;
use GDText\Enum\VerticalAlignment;

/**
 * @copyright   Copyright (c) EIRL Richard Déloge (richard@teknoo.software)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 *
 * @covers \GDText\Box
 */
class TextStrokedTest extends AbstractTestCase
{
    protected function mockBox($im): \GDText\Box
    {
        imagealphablending($im, true);
        imagesavealpha($im, true);

        $box = new Box($im);
        $box->setFontFace(__DIR__.'/LinLibertine_R.ttf'); // http://www.dafont.com/franchise.font
        $box->setFontColor(new Color(255, 75, 140));
        $box->setFontSize(16);
        $box->setBox(0, 135, imagesx($im), 70);
        $box->setTextAlign(HorizontalAlignment::Left, VerticalAlignment::Top);

        return $box;
    }

    public function testStroke(): void
    {
        $im = $this->openImageResource('owl_png24.png');
        $box = $this->mockBox($im);
        $box->setStrokeSize(10);
        $box->setLineHeight(2);
        $box->draw('Owls are birds from the order Strigiformes, which includes about 200 species.');

        self::assertImageEquals('test_wrap_stroked.png', $im);
    }
}
