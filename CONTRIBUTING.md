# Contributing

 * Coding standard for the project is [PSR-12](https://www.php-fig.org/psr/psr-12/), checked by `make qa`.
 * Any contribution must provide tests for additional introduced conditions.
 * Any un-confirmed issue needs a failing test case before being accepted.
 * Each bug fix must come in its own commit, with its non-regression tests.
 * Existing tests and reference images must not be modified: a failing existing test means a regression. If you
   think an existing test is wrong, explain why in the pull request.
 * Pull requests must be sent from a new hotfix/feature branch, not from `master`.

## Installation

To install the project and run the tests, you need to clone it first:

```sh
$ git clone https://github.com/TeknooSoftware/gd-text.git
```

You will then need to install the dependencies with composer:

```sh
$ cd gd-text
$ make depend
```

## Testing

The PHPUnit version to be used is the one installed as a dev- dependency via composer:

```sh
$ make test
```

`make test` runs PHPUnit with code coverage and requires Xdebug. Without Xdebug, run `vendor/bin/phpunit` directly.

Rendering tests compare the generated image with a reference image in `tests/images/<GD version>/`. Reference
images exist for GD 2.1 (`2.1.0`) and GD 2.3.x (`2.3.0`), tests are marked as incomplete with another version of GD
or when a reference image is missing.

Static analysis (PHPStan, PSR-12) and the dependencies audit are run with:

```sh
$ make qa
```

Accepted coverage for new contributions is 90%. Any contribution not satisfying this requirement
won't be merged.

For any questions, contact me : [richard@teknoo.software](richard@teknoo.software) :)

## Support this project

This project is free and will remain free, but it is developed on my personal time. 
If you like it and help me maintain it and evolve it, don't hesitate to support me on [Patreon](https://patreon.com/teknoo_software).
Thanks :) Richard. 
