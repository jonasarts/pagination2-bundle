<?php

/*
 * This file is part of the jonasarts Pagination bundle package.
 *
 * (c) Jonas Hauser <symfony@jonasarts.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace jonasarts\Bundle\PaginationBundle\Interfaces;

use jonasarts\Bundle\PaginationBundle\PaginationData\PaginationData;

/**
 * Counter interface.
 */
interface CounterInterface
{
    /**
     * Counter data to use.
     * 
     * @return PaginationData
     */
    public function getPaginationData();

    /**
     * Counter data to use.
     * 
     * @param PaginationData $paginationData
     */
    public function setPaginationData(PaginationData $paginationData);
}
