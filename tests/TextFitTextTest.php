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
 * @copyright   Copyright (c) EIRL Richard Déloge (richarddeloge@gmail.com)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 *
 * @link        http://teknoo.software/gd-text Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 *
 * @covers \GDText\Box
 */
class TextFitTextTest extends AbstractTestCase
{
    protected function mockBox($im): \GDText\Box
    {
        imagealphablending($im, true);
        imagesavealpha($im, true);

        $box = new Box($im);
        $box->setFontFace(__DIR__.'/LinLibertine_R.ttf'); // http://www.dafont.com/franchise.font
        $box->setFontColor(new Color(255, 75, 140));
        $box->setFontSize(16);
        $box->setBox(0, 135, imagesx($im), 100);
        $box->setTextAlign(HorizontalAlignment::Left, VerticalAlignment::Top);

        return $box;
    }

    public function testFitTextNoLimit(): void
    {
        $im = $this->openImageResource('owl_png24.png');
        $box = $this->mockBox($im);
        $box->drawFitFontSize('Owls are birds');

        self::assertImageEquals('test_wrap_fit_text_no_limit.png', $im);
    }

    public function testFitTextIncrease(): void
    {
        $im = $this->openImageResource('owl_png24.png');
        $box = $this->mockBox($im);
        $box->drawFitFontSize('Owls are birds', 1, 25, 10);

        self::assertImageEquals('test_wrap_fit_text_increase.png', $im);
    }

    public function testFitTextDecrease(): void
    {
        $im = $this->openImageResource('owl_png24.png');
        $box = $this->mockBox($im);
        $box->setBox(0, 135, imagesx($im), 10);
        $box->drawFitFontSize('Owls are birds', 1, 25, 8);

        self::assertImageEquals('test_wrap_fit_text_decrease.png', $im);
    }
}
