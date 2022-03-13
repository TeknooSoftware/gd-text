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
use GDText\Enum\HorizontalAlignment;
use GDText\Enum\VerticalAlignment;

/**
 * @covers \GDText\Box
 */
class TextAlignmentTest extends AbstractTestCase
{
    protected function mockBox($im)
    {
        imagealphablending($im, true);
        imagesavealpha($im, true);

        $box = new Box($im);
        $box->setFontFace(__DIR__.'/LinLibertine_R.ttf'); // http://www.dafont.com/franchise.font
        $box->setFontColor(new Color(255, 75, 140));
        $box->setFontSize(16);
        $box->setBackgroundColor(new Color(0, 0, 0));
        $box->setBox(0, 10, imagesx($im), 150);

        return $box;
    }

    public function testAlignment()
    {
        $xList = [HorizontalAlignment::Left, HorizontalAlignment::Center, HorizontalAlignment::Right];
        $yList = [VerticalAlignment::Top, VerticalAlignment::Center, VerticalAlignment::Bottom];

        foreach ($yList as $y) {
            foreach ($xList as $x) {
                $im = $this->openImageResource('owl_png24.png');
                $box = $this->mockBox($im);
                $box->setTextAlign($x, $y);
                $box->draw('Owls are birds from the order Strigiformes, which includes about 200 species.');

                self::assertImageEquals("test_align_{$y->value}_{$x->value}.png", $im);
            }
        }
    }
}
