# GD-text

## Overview
GD-text is a PHP library designed to add text to images with various effects (stroke, background, shadows) and alignments using the PHP GD extension. It is a fork of the original `GdText` library.

## Tech Stack
- **Language**: PHP 8.4+ (strictly typed)
- **Dependencies**: PHP GD extension (with Native TTF support)
- **Testing**: PHPUnit (unit & visual regression)
- **Static Analysis**: PHPStan, Rector

## Core Architecture
- **Namespace**: `GDText`
- **Core Component**: `GDText\Box` (manages image context and drawing operations)
- **Value Objects/Structs**:
  - `GDText\Struct\Point`
  - `GDText\Struct\Rectangle`
  - `GDText\Color`
- **Enumerations**:
  - `GDText\Enum\TextWrapping`
  - `GDText\Enum\VerticalAlignment`
  - `GDText\Enum\HorizontalAlignment`

## Directory Structure
- `src/`: Core business logic and implementation.
- `tests/`:
  - Unit tests (`*Test.php`)
  - Visual regression assets (`images/` directories containing reference PNGs).

## Development & Verification
- **Unit Testing**: All changes must be verified with `vendor/bin/phpunit`.
- **Visual Regression**: Since this library handles graphical rendering, any changes to drawing logic must be verified against existing reference images in `tests/images/`.
- **Static Analysis**: Ensure no errors are introduced in PHPStan or Rector checks.
