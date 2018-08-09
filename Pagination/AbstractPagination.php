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

use Countable;
use Iterator;
use ArrayAccess;
use jonasarts\Bundle\PaginationBundle\Pagination\PaginationInterface;

/**
 * AbstractPagination class.
 */
abstract class AbstractPagination implements PaginationInterface, Countable, Iterator, ArrayAccess
{
    /**
     * items is used by Countable, Iterator, ArrayAccess; contains only the itemPerPage items.
     * 
     * for pagination calculations:
     * - currentPageNumber is the number of the page for which the pagination window is calculated
     * - itemsPerPage is the number of items on one page
     * - totalItemsCount is the number of all items (NOT count, count is max. itemsPerPage!)
     */
    private $items = array();

    //
    private $currentPageNumber;

    //
    private $itemsPerPage;

    //
    private $pagesInRange;

    //
    private $totalItemsCount;

    /**
     * Get currently used page number.
     *
     * @return int
     * 
     * @see PaginationInterface
     */
    public function getCurrentPage()
    {
        return $this->currentPageNumber;
    }

    /**
     * {@inheritdoc}
     * 
     * @param int $pageNumber
     * @return self
     * 
     * @see PaginationInterface
     */
    public function setCurrentPage($pageNumber)
    {
        $this->currentPageNumber = $pageNumber;

        return $this;
    }

    /**
     * Get number of items per page.
     *
     * @return int
     * 
     * @see PaginationInterface
     */
    public function getPageSize()
    {
        return $this->itemsPerPage;
    }

    /**
     * {@inheritdoc}
     * 
     * @param int $itemsPerPage
     * @return self
     * 
     * @see PaginationInterface
     */
    public function setPageSize($itemsPerPage)
    {
        $this->itemsPerPage = $itemsPerPage;

        return $this;
    }

    /**
     * Get number of pages in sliding view.
     * 
     * @return int
     */
    public function getPageRange()
    {
        return $this->pagesInRange;
    }

    /**
     * Get number of pages in sliding view.
     * 
     * @param int $range
     * @return self
     */
    public function setPageRange($range)
    {
        $this->pagesInRange = intval(abs($range));

        return $this;
    }

    /**
     * {@inheritdoc}
     * 
     * @see PaginationInterface
     */
    public function getTotalItemsCount()
    {
        return $this->totalItemsCount;
    }

    /**
     * {@inheritdoc}
     * 
     * @see PaginationInterface
     */
    public function setTotalItemsCount($total)
    {
        $this->totalItemsCount = $total;

        return $this;
    }

    /**
     * Get current items.
     *
     * {@inheritdoc}
     * 
     * @see PaginationInterface
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * {@inheritdoc}
     * 
     * @see PaginationInterface
     */
    public function setItems($items)
    {
        if (!is_array($items) && !$items instanceof \Traversable) {
            throw new \UnexpectedValueException('Items must be an array type');
        }

        $this->items = $items;

        return $this;
    }

    /**
     * {@inheritdoc}
     * 
     * @see Countable
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * {@inheritdoc}
     * 
     * @see Iterator
     */
    public function current()
    {
        return current($this->items);
    }

    /**
     * {@inheritdoc}
     * 
     * @see Iterator
     */
    public function key()
    {
        return key($this->items);
    }

    /**
     * {@inheritdoc}
     * 
     * @see Iterator
     */
    public function next()
    {
        next($this->items);
    }

    /**
     * {@inheritdoc}
     * 
     * @see Iterator
     */
    public function rewind()
    {
        reset($this->items);
    }

    /**
     * {@inheritdoc}
     * 
     * @see Iterator
     */
    public function valid()
    {
        return key($this->items) !== null;
    }

    /**
     * {@inheritdoc}
     * 
     * @see ArrayAccess
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->items);
    }

    /**
     * {@inheritdoc}
     * 
     * @see ArrayAccess
     */
    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    /**
     * {@inheritdoc}
     * 
     * @see ArrayAccess
     */
    public function offsetSet($offset, $value)
    {
        if (null === $offset) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * {@inheritdoc}
     * 
     * @see ArrayAccess
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }
}
