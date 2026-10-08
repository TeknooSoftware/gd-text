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

        $this->assertImageEquals('test_wrap_stroked.png', $im);
    }

    private function hashImage(\GdImage $im): string
    {
        ob_start();
        imagepng($im);

        return hash('sha256', (string) ob_get_clean());
    }

    public static function provideNoStrokeSizes(): array
    {
        return [
            'zero' => [0],
            'negative' => [-3],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('provideNoStrokeSizes')]
    public function testNonPositiveStrokeSizeDrawsLikeNoStroke(int $size): void
    {
        $reference = $this->openImageResource('owl_png24.png');
        $this->mockBox($reference)->draw('Owls are birds');

        $im = $this->openImageResource('owl_png24.png');
        $box = $this->mockBox($im);
        $box->setStrokeSize($size);
        $box->setStrokeColor(new Color(0, 255, 0));
        $box->draw('Owls are birds');

        $this->assertSame($this->hashImage($reference), $this->hashImage($im));
    }
}
