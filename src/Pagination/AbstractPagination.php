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
    private array $items = array();

    /**
     * @var int
     */
    private int $currentPageNumber;

    /**
     * @var int
     */
    private int $itemsPerPage;

    /**
     * @var int
     */
    private int $pagesInRange;

    /**
     * @var int
     */
    private int $totalRecords;

    /**
     * Get currently used page number.
     *
     * @return int
     *
     * @see PaginationInterface
     */
    public function getCurrentPage(): int
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
    public function setCurrentPage(int $pageNumber): self
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
    public function getPageSize(): int
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
    public function setPageSize(int $itemsPerPage): self
    {
        $this->itemsPerPage = $itemsPerPage;

        return $this;
    }

    /**
     * Get number of pages in sliding view.
     *
     * @return int
     */
    public function getPageRangeSize(): int
    {
        return $this->pagesInRange;
    }

    /**
     * Get number of pages in sliding view.
     *
     * @param int $range
     * @return self
     */
    public function setPageRangeSize(int $range): self
    {
        $this->pagesInRange = intval(abs($range));

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @see PaginationInterface
     */
    public function getTotalRecords(): int
    {
        return $this->totalRecords;
    }

    /**
     * {@inheritdoc}
     *
     * @see PaginationInterface
     */
    public function setTotalRecords(int $total): self
    {
        $this->totalRecords = $total;

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
    public function setItems($items): self
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
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * {@inheritdoc}
     *
     * @see Iterator
     */
    public function current(): mixed
    {
        return current($this->items);
    }

    /**
     * {@inheritdoc}
     *
     * @see Iterator
     */
    public function key(): string|int|null
    {
        return key($this->items);
    }

    /**
     * {@inheritdoc}
     *
     * @see Iterator
     */
    public function next(): void
    {
        next($this->items);
    }

    /**
     * {@inheritdoc}
     *
     * @see Iterator
     */
    public function rewind(): void
    {
        reset($this->items);
    }

    /**
     * {@inheritdoc}
     *
     * @see Iterator
     */
    public function valid(): bool
    {
        return key($this->items) !== null;
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->items);
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess
     */
    public function offsetGet($offset): mixed
    {
        return $this->items[$offset];
    }

    /**
     * {@inheritdoc}
     *
     * @see ArrayAccess
     */
    public function offsetSet($offset, $value): void
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
    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }
}
