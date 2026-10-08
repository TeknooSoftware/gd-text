<?php

/*
 * GdText.
 *
 * LICENSE
 *
 * This source file is subject to the 3-Clause BSD license
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
 * @license     http://teknoo.software/license/bsd-3         3-Clause BSD License
 * @author      Richard Déloge <richard@teknoo.software>
 */

declare(strict_types=1);

namespace GDText\Tests;

use GDText\Box;
use GDText\Color;
use GDText\Enum\HorizontalAlignment;
use GDText\Enum\VerticalAlignment;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 * @license     http://teknoo.software/license/bsd-3         3-Clause BSD License
 * @author      Richard Déloge <richard@teknoo.software>
 *
 */
#[CoversClass(Box::class)]
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

    private function assertFitsInBox(\GDText\Struct\Rectangle $area, int $boxWidth, int $boxHeight): void
    {
        $this->assertLessThanOrEqual($boxWidth, $area->getWidth(), 'Drawn text is wider than the box');
        $this->assertLessThanOrEqual($boxHeight, $area->getHeight(), 'Drawn text is higher than the box');
    }

    public function testFitTextNoLimit(): void
    {
        $im = $this->openImageResource('owl_png24.png');
        $box = $this->mockBox($im);
        $usedFontSize = null;
        $area = $box->drawFitFontSize('Owls are birds', -1, -1, -1, $usedFontSize);

        // Without limit, the font size must grow from 16 until the text fills the box
        $this->assertGreaterThan(16, $usedFontSize);
        $this->assertFitsInBox($area, imagesx($im), 100);

        // ... and the next step must not fit anymore
        $box->setFontSize($usedFontSize + 1);
        $next = $box->calculate('Owls are birds');
        $this->assertTrue($next->getWidth() > imagesx($im) || $next->getHeight() > 100);

        $this->assertImageEquals('test_wrap_fit_text_no_limit.png', $im);
    }

    public function testFitTextIncrease(): void
    {
        $im = $this->openImageResource('owl_png24.png');
        $box = $this->mockBox($im);
        $usedFontSize = null;
        $area = $box->drawFitFontSize('Owls are birds', 1, 25, 10, $usedFontSize);

        // Sizes 16 to 25 all fit, the maximum must be reached
        $this->assertSame(25, $usedFontSize);
        $this->assertFitsInBox($area, imagesx($im), 100);

        $this->assertImageEquals('test_wrap_fit_text_increase.png', $im);
    }

    public function testFitTextDecrease(): void
    {
        $im = $this->openImageResource('owl_png24.png');
        $box = $this->mockBox($im);
        $box->setBox(0, 135, imagesx($im), 10);
        $usedFontSize = null;
        $area = $box->drawFitFontSize('Owls are birds', 1, 25, 8, $usedFontSize);

        // Only size 8 (the minimum) fits in a 10 pixels high box
        $this->assertSame(8, $usedFontSize);
        $this->assertFitsInBox($area, imagesx($im), 10);

        $this->assertImageEquals('test_wrap_fit_text_decrease.png', $im);
    }

    public function testFitTextRestoresInitialFontSize(): void
    {
        $im = $this->openImageResource('owl_png24.png');
        $box = $this->mockBox($im);
        $box->drawFitFontSize('Owls are birds', 1, 25, 10);

        $this->assertSame(16, $this->reflectFontSize($box));
    }

    private function reflectFontSize(Box $box): int
    {
        return new \ReflectionProperty($box, 'fontSize')->getValue($box);
    }

    public function testFitTextThatNeverFitsUsesExactlyTheMinimum(): void
    {
        $im = $this->openImageResource('owl_png24.png');
        $box = $this->mockBox($im);
        $box->setBox(0, 0, 1, 1);

        $usedFontSize = null;
        $box->drawFitFontSize('Owls are birds from the order Strigiformes', 5, -1, 4, $usedFontSize);
        $this->assertSame(4, $usedFontSize);

        $usedFontSize = null;
        $box->drawFitFontSize('Owls are birds from the order Strigiformes', 5, -1, -1, $usedFontSize);
        $this->assertSame(1, $usedFontSize);

        $usedFontSize = null;
        $box->drawFitFontSize('Owls are birds', 50, -1, 8, $usedFontSize);
        $this->assertSame(8, $usedFontSize);
    }

    public function testFitTextStartingAboveMaximumIsClampedToMaximum(): void
    {
        $im = $this->openImageResource('owl_png24.png');
        $box = $this->mockBox($im);

        $usedFontSize = null;
        $box->drawFitFontSize('Owls are birds', 1, 12, 8, $usedFontSize);
        $this->assertSame(12, $usedFontSize);
    }

    public function testFitTextWithNegativePrecisionBehavesLikePositive(): void
    {
        $im = $this->openImageResource('owl_png24.png');
        $box = $this->mockBox($im);

        $usedFontSize = null;
        $box->drawFitFontSize('Owls are birds', -1, 25, 10, $usedFontSize);
        $this->assertSame(25, $usedFontSize);
    }

    public function testFitTextWithZeroPrecisionIsRejected(): void
    {
        $im = $this->openImageResource('owl_png24.png');
        $box = $this->mockBox($im);

        $this->expectException(\InvalidArgumentException::class);
        $box->drawFitFontSize('Owls are birds', 0, 40, 5);
    }

    public function testFitTextThatNeverFitsWithoutMinimumStopsAtPositiveFontSize(): void
    {
        $im = $this->openImageResource('owl_png24.png');
        $box = $this->mockBox($im);
        $box->setBox(0, 0, 1, 1);

        $usedFontSize = null;
        $box->drawFitFontSize('Owls are birds from the order Strigiformes', 5, -1, -1, $usedFontSize);

        $this->assertGreaterThanOrEqual(1, $usedFontSize);
        $this->assertLessThanOrEqual(16, $usedFontSize);
    }

    public function testFitTextThatNeverFitsWithoutMinimumAndPrecisionOne(): void
    {
        $im = $this->openImageResource('owl_png24.png');
        $box = $this->mockBox($im);
        $box->setBox(0, 0, 1, 1);

        $usedFontSize = null;
        $box->drawFitFontSize('Owls are birds from the order Strigiformes', 1, -1, -1, $usedFontSize);

        $this->assertGreaterThanOrEqual(1, $usedFontSize);
        $this->assertLessThanOrEqual(16, $usedFontSize);
    }

    public function testFitTextThatNeverFitsNeverGoesBelowMinimum(): void
    {
        $im = $this->openImageResource('owl_png24.png');
        $box = $this->mockBox($im);
        $box->setBox(0, 0, 1, 1);

        $usedFontSize = null;
        $box->drawFitFontSize('Owls are birds from the order Strigiformes', 5, -1, 4, $usedFontSize);

        $this->assertGreaterThanOrEqual(4, $usedFontSize);
        $this->assertLessThanOrEqual(16, $usedFontSize);
    }

    public function testFitTextWithPrecisionLargerThanRangeNeverGoesBelowMinimum(): void
    {
        $im = $this->openImageResource('owl_png24.png');
        $box = $this->mockBox($im);
        $box->setBox(0, 0, 1, 1);

        $usedFontSize = null;
        $box->drawFitFontSize('Owls are birds', 50, -1, 8, $usedFontSize);

        $this->assertGreaterThanOrEqual(8, $usedFontSize);
        $this->assertLessThanOrEqual(16, $usedFontSize);
    }
}
