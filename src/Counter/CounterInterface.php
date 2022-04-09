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

namespace jonasarts\Bundle\PaginationBundle\Counter;

use jonasarts\Bundle\PaginationBundle\Pagination\PaginationData;

/**
 * Counter interface.
 */
interface CounterInterface
{
    /*
    'pageRecords' => 1,
    'totalRecords' => 2,
    'totalPages' => 3,
    */

    /**
     * Counter data to use.
     *
     * @return PaginationData
     */
    public function getPaginationData(): PaginationData;

    /**
     * Counter data to use.
     *
     * @param PaginationData $paginationData
     */
    public function setPaginationData(PaginationData $paginationData): self;
}
