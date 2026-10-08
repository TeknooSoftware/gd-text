Teknoo Software - GD-text library
=================================

[![Latest Stable Version](https://poser.pugx.org/teknoo/gd-text/v/stable)](https://packagist.org/packages/teknoo/gd-text)
[![Latest Unstable Version](https://poser.pugx.org/teknoo/gd-text/v/unstable)](https://packagist.org/packages/teknoo/gd-text)
[![Total Downloads](https://poser.pugx.org/teknoo/gd-text/downloads)](https://packagist.org/packages/teknoo/gd-text)
[![License](https://poser.pugx.org/teknoo/gd-text/license)](https://packagist.org/packages/teknoo/gd-text)
[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat)](https://github.com/phpstan/phpstan)

Fork from [`GdText library`](https://github.com/Pe46dro/gd-text) to add texts, with some effects and alignments in
images thanks to the `GdExtension`. 

Basic usage example
-------------------
```php
<?php
require __DIR__.'/../vendor/autoload.php';

use GDText\Box;
use GDText\Color;
use GDText\Enum\HorizontalAlignment;
use GDText\Enum\VerticalAlignment;

$im = imagecreatetruecolor(500, 500);
$backgroundColor = imagecolorallocate($im, 0, 18, 64);
imagefill($im, 0, 0, $backgroundColor);

$box = new Box($im);
$box->setFontFace(__DIR__.'/Franchise-Bold-hinted.ttf'); // http://www.dafont.com/franchise.font
$box->setFontColor(new Color(255, 75, 140));
$box->setTextShadow(new Color(0, 0, 0, 50), 2, 2);
$box->setFontSize(40);
$box->setBox(20, 20, 460, 460);
$box->setTextAlign(HorizontalAlignment::Left, VerticalAlignment::Top);
$box->draw("Franchise\nBold");

$box = new Box($im);
$box->setFontFace(__DIR__.'/Pacifico.ttf'); // http://www.dafont.com/pacifico.font
$box->setFontSize(80);
$box->setFontColor(new Color(255, 255, 255));
$box->setTextShadow(new Color(0, 0, 0, 50), 0, -2);
$box->setBox(20, 20, 460, 460);
$box->setTextAlign(HorizontalAlignment::Center, VerticalAlignment::Center);
$box->draw("Pacifico");

$box = new Box($im);
$box->setFontFace(__DIR__.'/Prisma.otf'); // http://www.dafont.com/prisma.font
$box->setFontSize(70);
$box->setFontColor(new Color(148, 212, 1));
$box->setTextShadow(new Color(0, 0, 0, 50), 0, -2);
$box->setBox(20, 20, 460, 460);
$box->setTextAlign(HorizontalAlignment::Right, VerticalAlignment::Bottom);
$box->draw("Prisma");

header("Content-type: image/png");
imagepng($im);
```

Example output:

![fonts example](examples/fonts.png)

Multilined text
---------------
```php
<?php
require __DIR__.'/../vendor/autoload.php';

use GDText\Box;
use GDText\Color;
use GDText\Enum\HorizontalAlignment;
use GDText\Enum\VerticalAlignment;

$im = imagecreatetruecolor(500, 500);
$backgroundColor = imagecolorallocate($im, 0, 18, 64);
imagefill($im, 0, 0, $backgroundColor);

$box = new Box($im);
$box->setFontFace(__DIR__.'/Minecraftia.ttf'); // http://www.dafont.com/minecraftia.font
$box->setFontColor(new Color(255, 75, 140));
$box->setTextShadow(new Color(0, 0, 0, 50), 2, 2);
$box->setFontSize(8);
$box->setLineHeight(1.5);
//$box->enableDebug();
$box->setBox(20, 20, 460, 460);
$box->setTextAlign(HorizontalAlignment::Left, VerticalAlignment::Top);
$box->draw(
    "    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla eleifend congue auctor. Nullam eget blandit magna. Fusce posuere lacus at orci blandit auctor. Aliquam erat volutpat. Cras pharetra aliquet leo. Cras tristique tellus sit amet vestibulum ullamcorper. Aenean quam erat, ullamcorper quis blandit id, sollicitudin lobortis orci. In non varius metus. Aenean varius porttitor augue, sit amet suscipit est posuere a. In mi leo, fermentum nec diam ut, lacinia laoreet enim. Fusce augue justo, tristique at elit ultricies, tincidunt bibendum erat.\n\n    Aenean feugiat dignissim dui non scelerisque. Cras vitae rhoncus sapien. Suspendisse sed ante elit. Duis id dolor metus. Vivamus congue metus nunc, ut consequat arcu dapibus vel. Ut sed ipsum sollicitudin, rutrum quam ac, fringilla risus. Phasellus non tincidunt leo, sodales venenatis nisl. Duis lorem odio, porta quis laoreet ut, tristique a justo. Morbi dictum dictum est ut facilisis. Duis suscipit sem ligula, at commodo risus pulvinar vehicula. Sed quis quam ac quam scelerisque dapibus id non justo. Sed mollis enim id neque tempus, a congue nulla blandit. Aliquam congue convallis lacinia. Aliquam commodo eleifend nisl a consectetur.\n\n    Maecenas sem nisl, adipiscing nec ante sed, sodales facilisis lectus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Ut bibendum malesuada ipsum eget vestibulum. Pellentesque interdum tempor libero eu sagittis. Suspendisse luctus nisi ante, eget tempus erat tristique sed. Duis nec pretium velit. Praesent ornare, tortor non sagittis sollicitudin, dolor quam scelerisque risus, eu consequat magna tellus id diam. Fusce auctor ultricies arcu, vel ullamcorper dui condimentum nec. Maecenas tempus, odio non ullamcorper dignissim, tellus eros elementum turpis, quis luctus ante libero et nisi.\n\n    Phasellus sed mauris vel lorem tristique tempor. Pellentesque ornare purus quis ullamcorper fermentum. Curabitur tortor mauris, semper ut erat vitae, venenatis congue eros. Ut imperdiet arcu risus, id dapibus lacus bibendum posuere. Etiam ac volutpat lectus. Vivamus in magna accumsan, dictum erat in, vehicula sem. Donec elementum lacinia fringilla. Vivamus luctus felis quis sollicitudin eleifend. Sed elementum, mi et interdum facilisis, nunc eros suscipit leo, eget convallis arcu nunc eget lectus. Quisque bibendum urna sit amet varius aliquam. In mollis ante sit amet luctus tincidunt."
);

header("Content-type: image/png;");
imagepng($im, null, 9, PNG_ALL_FILTERS);
```

Text stroke
-----------
```php
<?php
require __DIR__.'/../vendor/autoload.php';

use GDText\Box;
use GDText\Color;
use GDText\Enum\HorizontalAlignment;
use GDText\Enum\VerticalAlignment;

$im = imagecreatetruecolor(500, 500);
$backgroundColor = imagecolorallocate($im, 0, 18, 64);
imagefill($im, 0, 0, $backgroundColor);

$box = new Box($im);
$box->setFontFace(__DIR__.'/Elevant bold.ttf'); // http://www.dafont.com/elevant-by-pelash.font
$box->setFontSize(150);
$box->setFontColor(new Color(255, 255, 255));
$box->setBox(15, 20, 460, 460);
$box->setTextAlign(HorizontalAlignment::Center, VerticalAlignment::Center);

$box->setStrokeColor(new Color(255, 75, 140)); // Set stroke color
$box->setStrokeSize(3); // Stroke size in pixels

$box->draw("Elevant");

header("Content-type: image/png;");
imagepng($im, null, 9, PNG_ALL_FILTERS);
```

Text background
---------------
```php
<?php
require __DIR__.'/../vendor/autoload.php';

use GDText\Box;
use GDText\Color;
use GDText\Enum\HorizontalAlignment;
use GDText\Enum\VerticalAlignment;

$im = imagecreatetruecolor(500, 500);
$backgroundColor = imagecolorallocate($im, 0, 18, 64);
imagefill($im, 0, 0, $backgroundColor);

$box = new Box($im);
$box->setFontFace(__DIR__.'/fonts/BebasNeue.otf'); // http://www.dafont.com/bebas-neue.font
$box->setFontSize(100);
$box->setFontColor(new Color(255, 255, 255));
$box->setBox(15, 20, 460, 460);
$box->setTextAlign(HorizontalAlignment::Center, VerticalAlignment::Center);

$box->setBackgroundColor(new Color(255, 86, 77));

$box->draw("Bebas Neue");

header("Content-type: image/png;");
imagepng($im, null, 9, PNG_ALL_FILTERS);
```

Fit the text to the box
-----------------------
`drawFitFontSize()` looks for the biggest font size (by steps of `$precision`) for which the text fits in the box,
then draws it. Without `$maxFontSize`, the font size grows until the text fills the box. Without `$minFontSize`,
the font size can go down to 1. If the text never fits, it is drawn with the minimum font size and overflows.
The font size configured with `setFontSize()` is the starting point and is restored after drawing.

```php
<?php
use GDText\Box;

$box = new Box($im);
$box->setFontFace(__DIR__.'/Pacifico.ttf');
$box->setFontSize(16); // starting font size
$box->setBox(20, 20, 460, 460);

$usedFontSize = null;
$area = $box->drawFitFontSize(
    text: "Fit me",
    precision: 2,      // font size step, its sign is ignored, must not be 0
    maxFontSize: 120,  // or -1 for no limit
    minFontSize: 8,    // or -1 for no limit (never lower than 1)
    usedFontSize: $usedFontSize,
);
// $usedFontSize contains the font size used to draw the text
// $area is the GDText\Struct\Rectangle covered by the drawn text
```

API overview
------------
All setters of `GDText\Box` return the box itself and can be chained.

| Method | Description |
|--------|-------------|
| `setFontFace(string $path)` | Path to the TTF/OTF font file. Required before drawing. |
| `setFontSize(int $size)` | Font size in pixels (`>= 1`), default `12`. |
| `setFontColor(Color $color)` | Text color, default black. |
| `setBox(int $x, int $y, int $width, int $height)` | Area where the text is drawn, default `0, 0, 100, 100`. |
| `setTextAlign(HorizontalAlignment $x, VerticalAlignment $y)` | Alignment of the text in the box, default `Left`, `Top`. |
| `setTextWrapping(TextWrapping $wrapping)` | `WrapWithOverflow` (default) wraps words on the box width, a word longer than the box overflows. `NoWrap` draws the text as is. |
| `setLineHeight(float $ratio)` | Line height as a ratio of the font size (`> 0`), default `1.25`. |
| `setBaseline(float $ratio)` | Position of the baseline in the line, as a ratio of the line height, default `0.2`. |
| `setAngle(int $degrees)` | Rotation of each line, counter clockwise. The alignment is computed on the unrotated text. |
| `setStrokeColor(Color $color)`, `setStrokeSize(int $pixels)` | Outline of the text, default none (`0`). |
| `setTextShadow(Color $color, int $xShift, int $yShift)` | Shadow of the text, default none. |
| `setBackgroundColor(Color $color)` | Background behind each non empty line, default none. |
| `enableDebug()` | Fills the box and each line with random colors to visualize them. |
| `draw(string $text): Rectangle` | Draws the text and returns the area covered by it. |
| `drawFitFontSize(...)` | Draws the text with the biggest font size fitting the box, see above. |
| `calculate(string $text): Rectangle` | Returns the area that `draw()` would cover, without drawing anything. |

`GDText\Color` represents a 8-bit RGB color with an optional alpha channel (`0` opaque to `127` transparent):
`new Color(255, 75, 140)`, `new Color(0, 0, 0, 50)`, `Color::parseString('#ff4b8c')`, `Color::parseString('#abc')`,
`Color::fromHsl(0.5, 0.8, 0.3)` (each component between `0` and `1`). Invalid values raise an
`InvalidArgumentException`. On palette images whose palette is full, the closest color is used.

Security considerations
-----------------------
* `setFontFace()` passes the path as is to the GD extension. Depending on how GD was built, a name that is not a
  file path can be resolved through fontconfig. Never pass a user controlled value to this method.
* The drawing cost grows with the text length, the font size and the stroke size: a stroke of `n` pixels draws
  each line `(2n + 1)²` times. `drawFitFontSize()` measures the text once per step between the minimum and the
  maximum font size. Bound these values when they come from user input.

Support this project
---------------------
This project is free and will remain free. It is fully supported by commercial activities of SASU Teknoo Software
and EIRL Richard DELOGE. If you like it and help me maintain it and evolve it, don't hesitate to support me on
[Patreon](https://patreon.com/teknoo_software) or [Github](https://github.com/sponsors/TeknooSoftware).

Thanks :) Richard.

Credits
-------
EIRL Richard Déloge - <https://deloge.io> - Lead developer.
SASU Teknoo Software - <https://teknoo.software>

About Teknoo Software
---------------------
**Teknoo Software** is a PHP software editor, founded by Richard Déloge, as part of EIRL Richard Déloge.
Teknoo Software's goals : Provide to our partners and to the community a set of high quality services or software,
sharing knowledge and skills.

License
-------
GdText is licensed under the 3-Clause BSD License - see the [LICENSE](LICENSE) file for details.

Installation & Requirements
---------------------------
To install this library with composer, run this command :

    composer require teknoo/gd-text

This library requires :

    * PHP 8.5+
    * GD extension with Native TTF support

Demos
------
Line height demo:

![line height example](examples/lineheight.gif)

Text alignment demo:

![align example](examples/alignment.gif)

Text stroke demo:

![stroke example](examples/stroke.gif)

Text background demo:

![stroke example](examples/background.gif)

Debug mode enabled demo:

![debug example](examples/debug.png)

Contribute :)
-------------
You are welcome to contribute to this project. [Fork it on Github](CONTRIBUTING.md)
