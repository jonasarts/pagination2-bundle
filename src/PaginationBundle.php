<?php

declare(strict_types=1);

/*
 * This file is part of the jonasarts Pagination bundle package.
 *
 * (c) Jonas Hauser <symfony@jonasarts.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace jonasarts\Bundle\PaginationBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PaginationBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
