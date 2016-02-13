<?php

/*
 * This file is part of the jonasarts Pagination bundle package.
 *
 * (c) Jonas Hauser <symfony@jonasarts.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace jonasarts\Bundle\PaginationBundle\PaginationData;

/**
 * PaginationData class.
 */
class PaginationData
{
    /**
     * @var array
     */
    private $data;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->data = array();
    }

    /**
     * @return array
     */
    public function asArray()
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function getPageIndex()
    {
        return $this->data['page_index'];
    }

    /**
     * @param int $pageIndex
     *
     * @return CounterData
     */
    public function setPageIndex($pageIndex)
    {
        $this->data['page_index'] = $pageIndex;

        return $this;
    }

    /**
     * @return int
     */
    public function getPageRange()
    {
        return $this->data['page_range'];
    }

    /**
     * @param int $pageRange
     *
     * @return CounterData
     */
    public function setPageRange($pageRange)
    {
        $this->data['page_range'] = $pageRange;

        return $this;
    }

    /**
     * @return int
     */
    public function getPageSize()
    {
        return $this->data['page_size'];
    }

    /**
     * @param int $pageSize
     *
     * @return CounterData
     */
    public function setPageSize($pageSize)
    {
        $this->data['page_size'] = $pageSize;

        return $this;
    }

    /**
     * @return string
     */
    public function getSortName()
    {
        return $this->data['sort_name'];
    }

    /**
     * @param string $sortName
     *
     * @return CounterData
     */
    public function setSortName($sortName)
    {
        $this->data['sort_name'] = $sortName;

        return $this;
    }

    /**
     * @return string
     */
    public function getSortDirection()
    {
        return $this->data['sort_direction'];
    }

    /**
     * @param string $direction
     *
     * @return CounterData
     */
    public function setSortDirection($direction)
    {
        $this->data['sort_direction'] = $direction;

        return $this;
    }

    /**
     * @return int
     */
    public function getPageItemsCounter()
    {
        return $this->data['page_items_count'];
    }

    /**
     * @param interger $count
     *
     * @return CounterData
     */
    public function setPageItemsCount($count)
    {
        $this->data['page_items_count'] = $count;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalItemsCount()
    {
        return $this->data['total_items_count'];
    }

    /**
     * @param int $count
     *
     * @return CounterData
     */
    public function setTotalItemsCount($count)
    {
        $this->data['total_items_count'] = $count;

        return $this;
    }
}
