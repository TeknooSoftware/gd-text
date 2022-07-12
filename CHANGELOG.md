# Teknoo Software - Gd-Text - Change Log

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

