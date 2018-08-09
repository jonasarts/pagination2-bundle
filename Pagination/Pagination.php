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

use Closure;

/**
 * Pagination class.
 */
class Pagination extends AbstractPagination
{
    /**
     * Closure which is executed to render pagination.
     *
     * @var Closure
     */
    public $renderer;

    /**
     * Constructor.
     */
    public function __construct(array $items, $totalItemsCount)
    {
        $this->setItems($items);
        $this->setTotalItemsCount($totalItemsCount);
    }

    /**
     * Renders the pagination.
     * 
     * @return string
     */
    public function __toString()
    {
        $data = $this->getData();

        $output = '';

        if (!$this->renderer instanceof Closure) {
            $output = 'add a renderer in order to render a template';
        } else {
            try {
                $output = call_user_func($this->renderer, $data);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        return $output;
    }

    /**
     * Populates an pagination 'viewdata' array.
     *  
     * @return array
     */
    private function getData()
    {
        // how many pages fit into all items?
        if ($this->getPageSize() > 0) {
            $page_count = intval(ceil($this->getTotalItemsCount() / $this->getPageSize()));
        } else {
            $page_count = 1;
        }
        // current page
        $current = $this->getCurrentPage();
        // page range
        $range = $this->getPageRange();

        // range = how many pages around the current page?
        if ($range > $page_count) {
            $range = $page_count;
        }

        // above / below
        $delta = ceil($range / 2);

        // calculate pages in range
        if ($current - $delta > $page_count - $range) {
            $pages = range($page_count - $range + 1, $page_count);
        } else {
            if ($current - $delta < 0) {
                $delta = $current;
            }

            $offset = $current - $delta;
            $pages = range($offset + 1, $offset + $range);
        }

        $viewData = array(
            'last' => $page_count,
            'current' => $current,
            'pageSize' => $this->getPageSize(),
            'first' => 1,
            'pageCount' => $page_count,
            'totalCount' => $this->getTotalItemsCount(),
        );
        //$viewData = array_merge($viewData, $this->paginatorOptions, $this->customParameters);

        if ($current - 1 > 0) {
            $viewData['previous'] = $current - 1;
        }

        if ($current + 1 <= $page_count) {
            $viewData['next'] = $current + 1;
        }

        $viewData['pagesInRange'] = $pages;
        $viewData['firstPageInRange'] = min($pages);
        $viewData['lastPageInRange'] = max($pages);

        if ($this->getItems() !== null) {
            $viewData['firstItemNumber'] = (($current - 1) * $this->getPageSize()) + 1; // first item on this page
            if ($current + 1 <= $page_count) {
                // get the item count on this page

                $viewData['currentItemCount'] = $this->getPageSize();
            } else {
                $viewData['currentItemCount'] = $this->getTotalItemsCount() - ($viewData['firstItemNumber'] - 1);
            }
            $viewData['lastItemNumber'] = ($viewData['firstItemNumber'] - 1) + $viewData['currentItemCount']; // last item on this page
        }

        return $viewData;
    }
}
