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

declare(strict_types=1);

namespace GDText\Tests;

use GDText\Box;
use GDText\Color;
use GDText\Enum\HorizontalAlignment;
use GDText\Enum\VerticalAlignment;
use GDText\Struct\Rectangle;
use GdImage;
use PHPUnit\Framework\Attributes\CoversClass;

use function imagecolorallocate;
use function imagecolorat;
use function imagecolorsforindex;
use function imagecreatetruecolor;
use function imagefill;

/**
 * @copyright   Copyright (c) EIRL Richard Déloge (https://deloge.io - richard@deloge.io)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software - contact@teknoo.software)
 * @license     http://teknoo.software/license/bsd-3         3-Clause BSD License
 * @author      Richard Déloge <richard@teknoo.software>
 *
 */
#[CoversClass(Box::class)]
class TextBackgroundTest extends AbstractTestCase
{
    private const array BACKGROUND = [1, 2, 3];

    private function buildImage(): GdImage
    {
        $im = imagecreatetruecolor(200, 100);
        imagefill($im, 0, 0, imagecolorallocate($im, 255, 255, 255));

        return $im;
    }

    private function buildBox(GdImage $im): Box
    {
        $box = new Box($im);
        $box->setFontFace(__DIR__ . '/LinLibertine_R.ttf');
        $box->setFontColor(new Color(200, 200, 200));
        $box->setBackgroundColor(new Color(...self::BACKGROUND));
        $box->setFontSize(16);
        $box->setBox(0, 0, 200, 100);
        $box->setTextAlign(HorizontalAlignment::Left, VerticalAlignment::Top);

        return $box;
    }

    private function countBackgroundPixels(GdImage $im, Rectangle $area): int
    {
        $count = 0;
        for ($x = $area->getLeft(); $x < $area->getRight(); ++$x) {
            for ($y = $area->getTop(); $y < $area->getBottom(); ++$y) {
                $color = imagecolorsforindex($im, imagecolorat($im, $x, $y));
                if ([$color['red'], $color['green'], $color['blue']] === self::BACKGROUND) {
                    ++$count;
                }
            }
        }

        return $count;
    }

    public function testBackgroundIsDrawnBehindRegularText(): void
    {
        $im = $this->buildImage();
        $area = $this->buildBox($im)->draw('1');

        $this->assertGreaterThan(0, $this->countBackgroundPixels($im, $area));
    }

    public function testBackgroundIsDrawnBehindTextZero(): void
    {
        $im = $this->buildImage();
        $area = $this->buildBox($im)->draw('0');

        $this->assertGreaterThan(0, $this->countBackgroundPixels($im, $area));
    }

    public function testBackgroundIsDrawnBehindMultilineTextWithZeroLine(): void
    {
        $im = $this->buildImage();
        $box = $this->buildBox($im);
        $area = $box->draw("1\n0");

        // Each line is 16 * 1.25 = 20 pixels high, the second line (text "0") must have its own background
        $secondLine = new Rectangle($area->getLeft(), $area->getTop() + 20, $area->getWidth(), 20);
        $this->assertGreaterThan(0, $this->countBackgroundPixels($im, $secondLine));
    }

    public function testNoBackgroundForEmptyText(): void
    {
        $im = $this->buildImage();
        $box = $this->buildBox($im);
        $box->setBox(0, 0, 200, 100);
        $box->draw('');

        $this->assertSame(0, $this->countBackgroundPixels($im, new Rectangle(0, 0, 200, 100)));
    }
}
