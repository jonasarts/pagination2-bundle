<?php

/*
 * This file is part of the jonasarts Pagination bundle package.
 *
 * (c) Jonas Hauser <symfony@jonasarts.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace jonasarts\Bundle\PaginationBundle\Counter;

use jonasarts\Bundle\PaginationBundle\Interfaces\CounterInterface;
use jonasarts\Bundle\PaginationBundle\PaginationData\PaginationData;

/**
 * AbstractCounter class.
 */
abstract class AbstractCounter implements CounterInterface
{
    /**
     * @var PaginationData
     */
    private $pagination_data;

    /**
     * @return PaginationData
     */
    public function getPaginationData()
    {
        return $this->pagination_data;
    }

    /**
     * @param PaginationData $paginationData
     *
     * @return AbstractCounter
     */
    public function setPaginationData(PaginationData $paginationData)
    {
        $this->pagination_data = $paginationData;

        return $this;
    }
}
