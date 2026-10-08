# Teknoo Software - Gd-Text - Change Log

## [4.0.0] - 2026-10-08
### Stable Release
- Drop support of PHP 8.4
- Requires PHP 8.5
- Use `#[\NoDiscard]` on `Box::calculate()` and `Color::toArray()`
- Remove unused dev dependency `symfony/property-access`
- Fix `Color::parseString()` to reject non hexadecimal strings instead of parsing them as black with a deprecation
- Fix `Color::fromHsl()` with a hue of 1.0 and reject saturation or lightness outside `[0, 1]` with a clear message
- Fix `Color::getIndex()` on images with a full palette: the closest color is used instead of the palette index 0
- Fix missing line background for the text `"0"`
- Fix `Box::calculate()` drawing debug rectangles in debug mode
- Fix infinite loop in `Box::drawFitFontSize()` with a precision of 0 (an `InvalidArgumentException` is raised)
- Fix endless decrement in `Box::drawFitFontSize()` for a text that never fits (the font size is never lower than 1)
- Fix off-by-one font sizes in `Box::drawFitFontSize()`: without maximum the text now grows until it fills the box,
  the maximum and minimum font sizes are reachable, the initial font size is clamped to the `[min, max]` range
- `Box::setFontSize()` rejects values lower than 1, `Box::setLineHeight()` rejects values lower or equal to 0
- Resolve colors' indexes once per drawing instead of once per stroke pixel
- Fix documentation (README examples used strings instead of enums, requirements, API overview, security notes)
- Remove unused reference images for GD 2.3.3 (tests use the GD 2.3.0 references for all GD 2.3.x)

## [3.0.1] - 2025-12-02
### Stable Release
- Update dev libraries

## [3.0.0] - 2025-08-04
### Stable Release
- Drop support of PHP 8.3
- Requires PHP 8.4
- Update to PHPStan 2
- Fix some QA issues
- Switch license from MIT to 3-Clause BSD

## [2.0.13] - 2025-02-07
### Stable Release
- Update dev lib requirements
  - Require Symfony libraries 6.4 or 7.2
  - Update to PHPUnit 12
- Drop support of PHP 8.2
  - The library stay usable with PHP 8.2, without any waranties and tests
  - In the next major release, Support of PHP 8.2 will be dropped

## [2.0.12] - 2024-09-24
### Stable Release
- Remove deprecations about PHP 8.4

## [2.0.11] - 2024-07-22
### Stable Release
- Update dev lib requirements
- Fix issues with GD 2.3.3

## [2.0.10] - 2023-11-29
### Stable Release
- Update dev lib requirements

## [2.0.9] - 2023-05-16
### Stable Release
- Use sha256 instead of sha1 in test

## [2.0.8] - 2023-05-15
### Stable Release
- Update dev lib requirements
- Update copyrights

## [2.0.7] - 2023-04-16
### Stable Release
- Update dev lib requirements
- Support PHPUnit 10.1+
- Migrate phpunit.xml

## [2.0.6] - 2023-03-12
### Stable Release
- Q/A

## [2.0.5] - 2023-02-11
### Stable Release
- Remove phpcpd and upgrade phpunit.xml

## [2.0.4] - 2023-02-03
### Stable Release
- Update dev libs to support PHPUnit 10 and remove unused phploc

## [2.0.3] - 2022-12-15
### Stable Release
- Some QA fixes

## [2.0.2] - 2022-07-12
### Stable Release
- Fix supports with 2.3.3

## [2.0.1] - 2022-06-17
### Stable Release
- Clean code and test thanks to Rector
- Update libs requirements

## [2.0.0] - 2022-03-10
### Stable Release
- Require PHP 8.1+.
- Fix all deprecated on PHP8.1+
- Rewrite the library to simplify and to use last PHP's improvements, whose
  - Use `¶eadonly` on `Point` and `Rectangle`
  - `match` instead if cascading of switch.
  - Type hinting on method's parameters and return values
- Follow PSR 12
- Replace `HorizontalAlignment` by an backed enum (on string)
- Replace `VerticalAlignment` by an backed enum (on string)
- Replace `TextWrapping` by an backed enum (on string)
- Complete coverage
- Fix bug in `drawFitFontSize` with negative increment step.

## [1.2.0] - 2020-12-06
### Stable Release from Pe46dro

