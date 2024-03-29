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
    public function getCurrentPage(): int;

    /**
     * On which page currently?
     *
     * @param int $pageNumber
     * @return self
     */
    public function setCurrentPage(int $pageNumber): self;

    /**
     * How many items per page?
     *
     * @return int
     */
    public function getPageSize(): int;

    /**
     * How many items per page?
     *
     * @param int $itemsPerPage
     * @return self
     */
    public function setPageSize(int $itemsPerPage): self;

    /**
     * How many pages in sliding navigation.
     *
     * @return int
     */
    public function getPageRangeSize(): int;

    /**
     * How many pages in sliding navigation.
     *
     * @param int $pagesInRange
     * @return self
     */
    public function setPageRangeSize(int $pagesInRange): self;

    /**
     * How many items in total?
     *
     * @return int
     */
    public function getTotalRecords(): int;

    /**
     * How many items in total?
     *
     * @param int $total
     * @return self
     */
    public function setTotalRecords(int $total): self;

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
    public function setItems($items): self;
}
