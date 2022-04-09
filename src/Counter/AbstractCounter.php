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

use jonasarts\Bundle\PaginationBundle\Counter\CounterInterface;
use jonasarts\Bundle\PaginationBundle\Pagination\PaginationData;

/**
 * AbstractCounter class.
 */
abstract class AbstractCounter implements CounterInterface
{
    /*
    'pageRecords' => 1,
    'totalRecords' => 2,
    'totalPages' => 3,
    */

    /**
     * @var PaginationData
     */
    private $pagination_data;

    /**
     * @return PaginationData
     */
    public function getPaginationData(): PaginationData
    {
        return $this->pagination_data;
    }

    /**
     * @param PaginationData $paginationData
     * @return self
     */
    public function setPaginationData(PaginationData $paginationData): self
    {
        $this->pagination_data = $paginationData;

        return $this;
    }
}
