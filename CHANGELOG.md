# Teknoo Software - Gd-Text - Change Log

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

