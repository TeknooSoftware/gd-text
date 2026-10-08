# Teknoo Software - Gd-Text - Change Log


## [4.0.0] - 2026-10-08
### Stable Release

#### Security
- Fix a denial of service in `Box::drawFitFontSize()`: a precision of `0` caused an infinite loop, an
  `InvalidArgumentException` is now raised.
- Fix a denial of service in `Box::drawFitFontSize()`: a text that never fits in the box was decremented endlessly
  (and could be drawn with a negative font size), the font size is now never lower than `1` (or `$minFontSize`).

#### Evolution
- Drop support of PHP 8.4, PHP 8.5 is required.
- Use `#[\NoDiscard]` on `Box::calculate()` and `Color::toArray()`.
- `Box::setFontSize()` rejects values lower than `1` and `Box::setLineHeight()` rejects values lower or equal to
  `0` with an `InvalidArgumentException`.
- `Color::parseString()` rejects non hexadecimal strings with an `InvalidArgumentException` instead of parsing them
  as black with a deprecation notice.
- `Color::fromHsl()` rejects a saturation or a lightness outside `[0, 1]` with an explicit message.
- Resolve color indexes once per drawing instead of once per stroke pixel.
- Remove the unused dev dependency `symfony/property-access`.
- Remove the unused reference images for GD 2.3.3 (tests use the GD 2.3.0 references for all GD 2.3.x versions),
  tests are marked as incomplete when a reference image is missing for the running GD version.
- Complete the test coverage to 100%.

#### Documentation
- Fix the README: examples used strings instead of enums for the alignment, wrong PHP requirement and license
  reference, missing API overview (`drawFitFontSize()`, `calculate()`, `setTextWrapping()`, `setAngle()`,
  `setBaseline()`, `setLineHeight()`, `enableDebug()`), new security considerations.
- Update `CONTRIBUTING.md` (PSR-12, `make` targets, non-regression rules) and `AGENTS.md`.
- Update `SECURITY.md`: 4.0.x is supported, 3.0.x only receives security fixes.

#### Fix
- Fix `Color::fromHsl()` with a hue of `1.0`, it is now equivalent to a hue of `0.0` instead of raising an
  exception.
- Fix `Color::getIndex()` on images with a full palette: the closest color is used instead of the palette index
  `0`.
- Fix the missing line background for the text `"0"`.
- Fix `Box::calculate()` drawing the debug rectangles on the image in debug mode.
- Fix off-by-one font sizes in `Box::drawFitFontSize()`: without maximum the text now grows until it fills the
  box, the maximum and minimum font sizes are reachable and the initial font size is clamped to the
  `[min, max]` range.

## [3.0.1] - 2025-12-02
### Stable Release

#### Evolution
- Update dev libraries.

## [3.0.0] - 2025-08-04
### Stable Release

#### Evolution
- Drop support of PHP 8.3, PHP 8.4 is required.
- Update to PHPStan 2.
- Switch license from MIT to 3-Clause BSD.

#### Fix
- Fix some QA issues.

## [2.0.13] - 2025-02-07
### Stable Release

#### Evolution
- Update dev lib requirements:
  - Require Symfony libraries 6.4 or 7.2.
  - Update to PHPUnit 12.
- Drop support of PHP 8.2:
  - The library stays usable with PHP 8.2, without any warranties and tests.
  - In the next major release, support of PHP 8.2 will be dropped.

## [2.0.12] - 2024-09-24
### Stable Release

#### Fix
- Remove deprecations about PHP 8.4.

## [2.0.11] - 2024-07-22
### Stable Release

#### Evolution
- Update dev lib requirements.

#### Fix
- Fix issues with GD 2.3.3.

## [2.0.10] - 2023-11-29
### Stable Release

#### Evolution
- Update dev lib requirements.

## [2.0.9] - 2023-05-16
### Stable Release

#### Evolution
- Use sha256 instead of sha1 in tests.

## [2.0.8] - 2023-05-15
### Stable Release

#### Evolution
- Update dev lib requirements.

#### Documentation
- Update copyrights.

## [2.0.7] - 2023-04-16
### Stable Release

#### Evolution
- Update dev lib requirements.
- Support PHPUnit 10.1+.
- Migrate `phpunit.xml`.

## [2.0.6] - 2023-03-12
### Stable Release

#### Fix
- QA fixes.

## [2.0.5] - 2023-02-11
### Stable Release

#### Evolution
- Remove phpcpd and upgrade `phpunit.xml`.

## [2.0.4] - 2023-02-03
### Stable Release

#### Evolution
- Update dev libs to support PHPUnit 10 and remove unused phploc.

## [2.0.3] - 2022-12-15
### Stable Release

#### Fix
- Some QA fixes.

## [2.0.2] - 2022-07-12
### Stable Release

#### Fix
- Fix support of GD 2.3.3.

## [2.0.1] - 2022-06-17
### Stable Release

#### Evolution
- Clean code and tests thanks to Rector.
- Update libs requirements.

## [2.0.0] - 2022-03-10
### Stable Release

#### Evolution
- Require PHP 8.1+.
- Rewrite the library to simplify it and to use the last PHP improvements:
  - Use `readonly` on `Point` and `Rectangle`.
  - `match` instead of cascading `switch`.
  - Type hinting on methods' parameters and return values.
- Follow PSR-12.
- Replace `HorizontalAlignment` by a backed enum (on string).
- Replace `VerticalAlignment` by a backed enum (on string).
- Replace `TextWrapping` by a backed enum (on string).
- Complete coverage.

#### Fix
- Fix all deprecations on PHP 8.1+.
- Fix bug in `drawFitFontSize()` with a negative increment step.

## [1.2.0] - 2020-12-06
### Stable Release from Pe46dro
