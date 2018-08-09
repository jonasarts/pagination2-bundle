<?php

/*
 * This file is part of the jonasarts Pagination bundle package.
 *
 * (c) Jonas Hauser <symfony@jonasarts.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace jonasarts\Bundle\PaginationBundle\Pagination;

/**
 * Pagination interface.
 */
interface PaginationInterface
{
    /**
     * On which page currently?
     * 
     * @return int
     */
    public function getCurrentPage();

    /**
     * On which page currently?
     * 
     * @param int $pageNumber
     * @return self
     */
    public function setCurrentPage($pageNumber);

    /**
     * How many items per page?
     * 
     * @return int
     */
    public function getPageSize();

    /**
     * How many items per page?
     * 
     * @param int $itemsPerPage
     * @return self
     */
    public function setPageSize($itemsPerPage);

    /**
     * How many pages in sliding navigation.
     * 
     * @return int
     */
    public function getPageRange();

    /**
     * How many pages in sliding navigation.
     * 
     * @param int $pagesInRange
     * @return self
     */
    public function setPageRange($pagesInRange);

    /**
     * How many items in total?
     * 
     * @return int
     */
    public function getTotalItemsCount();

    /**
     * How many items in total?
     * 
     * @param int $total
     * @return self
     */
    public function setTotalItemsCount($total);

    /**
     * The items (required for Countable, Iterator, ArrayAccess).
     * 
     * @return mixed
     */
    public function getItems();

    /**
     * The items (required for Countable, Iterator, ArrayAccess).
     * 
     * @param mixed $items
     * @return self
     */
    public function setItems($items);
}
