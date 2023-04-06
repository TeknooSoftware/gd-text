<?php

/*
 * GdText.
 *
 * LICENSE
 *
 * This source file is subject to the MIT license
 * it is available in LICENSE file at the root of this package
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to richard@teknoo.software so we can send you a copy immediately.
 *
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (richard@teknoo.software)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 * @copyright   Copyright (c) Pe46dro (https://github.com/Pe46dro/gd-text) [author of v1.x]
 * @copyright   Copyright (c) Stil (https://github.com/stil/gd-text) [author of v1.x]
 *
 * @link        http://teknoo.software/gd-text Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */

declare(strict_types=1);

namespace GDText\Struct;

/**
 * Rectangle representation used in box to print text in area
 *
 * @copyright   Copyright (c) EIRL Richard Déloge (richard@teknoo.software)
 * @copyright   Copyright (c) SASU Teknoo Software (https://teknoo.software)
 * @copyright   Copyright (c) Pe46dro (https://github.com/Pe46dro/gd-text) [author of v1.x]
 * @copyright   Copyright (c) Stil (https://github.com/stil/gd-text) [author of v1.x]
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richard@teknoo.software>
 */
class Rectangle extends Point
{
    public function __construct(
        int $x,
        int $y,
        private readonly int $width,
        private readonly int $height
    ) {
        parent::__construct($x, $y);
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getLeft(): int
    {
        return $this->getX();
    }

    public function getTop(): int
    {
        return $this->getY();
    }

    public function getRight(): int
    {
        return $this->getX() + $this->width;
    }

    public function getBottom(): int
    {
        return $this->getY() + $this->height;
    }
}
