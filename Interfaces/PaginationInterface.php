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

/**
 * Pagination interface.
 */
interface PaginationInterface
{
    /**
     * On which page currently?
     * 
     * @return integer
     */
    public function getCurrentPage();

    /**
     * On which page currently?
     * 
     * @param integer $pageNumber
     */
    public function setCurrentPage($pageNumber);

    /**
     * How many items per page?
     * 
     * @return integer
     */
    public function getPageSize();

    /**
     * How many items per page?
     * 
     * @param integer $itemsPerPage
     */
    public function setPageSize($itemsPerPage);

    /**
     * How many pages in sliding navigation.
     * 
     * @return integer
     */
    public function getPageRange();

    /**
     * How many pages in sliding navigation.
     * 
     * @param integer $pagesInRange
     */
    public function setPageRange($pagesInRange);

    /**
     * How many items in total?
     * 
     * @return integer
     */
    public function getTotalItemsCount();

    /**
     * How many items in total?
     * 
     * @param integer $total
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
     */
    public function setItems($items);
}
