# GD-text

## Overview
GD-text is a PHP library designed to add text to images with various effects (stroke, background, shadows) and alignments using the PHP GD extension. It is a fork of the original `GdText` library.

## Tech Stack
- **Language**: PHP 8.5+ (strictly typed, `declare(strict_types=1)` in every file)
- **Dependencies**: PHP GD extension (with Native TTF support). No runtime dependency besides GD.
- **Testing**: PHPUnit 13 (unit, functional & visual regression)
- **Static Analysis**: PHPStan (level max, `phpstan.neon`), PHP_CodeSniffer (PSR-12). Rector is not a dependency of
  the project: a local `rector.php` may exist on a developer machine but it is ignored by git.

## Core Architecture
- **Namespace**: `GDText`
- **Core Component**: `GDText\Box` (manages image context and drawing operations: `draw()`, `drawFitFontSize()`,
  `calculate()`)
- **Value Objects/Structs**:
  - `GDText\Struct\Point`
  - `GDText\Struct\Rectangle`
  - `GDText\Color` (RGB + optional alpha, `parseString()`, `fromHsl()`, `getIndex()`)
- **Enumerations**:
  - `GDText\Enum\TextWrapping`
  - `GDText\Enum\VerticalAlignment`
  - `GDText\Enum\HorizontalAlignment`
- **Exception**: `GDText\Exception\NoBoxException` (GD could not measure the text, e.g. missing font file).
  Invalid arguments raise `InvalidArgumentException`.

## Directory Structure
- `src/`: Core business logic and implementation.
- `tests/`:
  - Unit and functional tests (`*Test.php`), `AbstractTestCase` provides image helpers.
  - `LinLibertine_R.ttf`: font used by the tests.
  - `images/`: source images (`owl*.png`, `owl.gif`) and reference images for visual regression, one directory per
    GD version: `2.1.0/` (GD 2.1) and `2.3.0/` (GD 2.3.x). `AbstractTestCase::assertImageEquals()` compares the
    sha256 of the generated PNG with the reference of the running GD version and marks the test as incomplete for
    other GD versions or when the reference image is missing.
- `examples/`: images used by the README.
- `Makefile`: `make depend` (composer), `make test` (PHPUnit with coverage, needs Xdebug), `make qa`
  (lint + PHPStan + phpcs PSR-12 + composer audit), `make qa-offline` (without audit).

## Development & Verification
- **Unit Testing**: All changes must be verified with `make test` (or `vendor/bin/phpunit -c phpunit.xml` without
  Xdebug). `phpunit.xml` fails on any deprecation, notice or warning.
- **Visual Regression**: Since this library handles graphical rendering, any changes to drawing logic must be
  verified against existing reference images in `tests/images/`. Reference images can only be regenerated for the
  GD version available locally (check with `php -r 'echo gd_info()["GD Version"];'`).
- **Static Analysis**: Ensure no errors are introduced by running `make qa`.

## Rules
- The public API and the nominal behaviour must not break: new validations may reject invalid values, but valid
  calls must keep producing the same rendering.
- Each bug fix must come in its own commit, with its non-regression tests (PHPUnit). Prefer assertions on the
  returned `Rectangle`, on `$usedFontSize` or on pixel colors (independent from the GD version) over new reference
  images.
- Never modify an existing test or an existing reference image: a failing existing test means a regression. If a
  test looks wrong (true negative), stop and ask before changing it.
- Inputs coming from users must be bounded by the caller: font path, stroke size and `drawFitFontSize()` ranges
  (see "Security considerations" in `README.md`).
