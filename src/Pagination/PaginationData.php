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

use Hasch\Framework\Helper\FilterHelper;
use Hasch\Security\Model\UserInterface;
use jonasarts\Bundle\RegistryBundle\Registry\AbstractRegistry;

/**
 * PaginationData, use for 
 * - configuration before request (request -> configuration)
 * - pass configuration to paginator (configuration -> doctrine)
 * - get additional geometry after loading entities (doctrine -> geometry)
 * 
 * Avail configuration values (to set before passing AbstractEntityRepository.paginate())
 * - getPageSizes (get/set)
 * - getPageIndex (get/set)
 * - getPageSize  (get/set)
 * - getRangeSize (get/set)
 * 
 * Avail doctrine result values (set by AbstractEntityRepository.paginate())
 * - setPageRecords
 * - setTotalRecords
 * - setTotalPages
 * 
 * Class calculates pagination 'geometry' after doctrine paginator has loaded the entities automatically on
 * - getPages
 * - getPaginationRangeStartPage
 * - getPaginationRangeEndPage
 * - paginationRangeIncludesFirst
 * - paginationRangeIncludesLast
 * 
 * 
 * Additional data fields
 * 
 * - for UI values
 * -- sortField
 * -- sortDirection
 * 
 * - for sql
 * -- sqlSortField : string
 * -- sqlSortDirection : string
 * -- sqlSearchString : string
 * -- sqlFilter : array
 * 
 */
class PaginationData
{
    // https://stackoverflow.com/questions/1856785/characters-allowed-in-a-url
    // unreserved  = ALPHA / DIGIT / "-" / "." / "_" / "~"     <---This seems like a practical shortcut, most closely resembling original answer


    const SORT_FIELD_DIRECTION_SEPARATOR = ".";
    // the separator must not be used anywhere in the field_name !!!
    const SORT_FIELDS_SEPARATOR = "---";

    /**
     * @var array
     */
    private array $data;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->data = array(
            'page_sizes' => array(10, 20, 50, 100),
            'page_index' => 0,
            'page_size' => 0,
            'range_size' => 0,

            'page_records' => 0,
            'total_records' => 0,
            'total_pages' => 0,

            'sort' => [], // new sort field array [['field' => 'direction],['field' => 'direction']]
            // TODO remove
            'sort_field' => null,
            'sort_direction' => null,
        );

        $this->data['sqlSort'] = []; // new sort field array [['field' => 'direction],['field' => 'direction']]
        // TODO remove
        $this->data['sqlSortField'] = null;
        $this->data['sqlSortDirection'] = null;

        $this->data['sqlSearchString'] = null;
        $this->data['sqlFilter'] = array();

        $this->resetPaginationData();
    }

    /**
     * @return array
     */
    public function asArray(): array
    {
        return $this->data;
    }

    /**
     * pagination configuration values
     */

    /**
     * Array with possible paginator range sizes. (Ex.: 10, 20, 50, 100)
     * 
     * @return array
     */
    public function getPageSizes(): array
    {
        return $this->data['page_sizes'];
    }

    /**
     * @param array $pageSizes
     * @return self
     */
    public function setPageSizes(array $pageSizes): self
    {
        $this->data['page_sizes'] = $pageSizes;

        return $this;
    }

    /**
     * The current page shown in the paginator
     * 
     * Ex.: 1
     * 
     * @return int
     */
    public function getPageIndex(): int
    {
        return $this->data['page_index'];
    }

    /**
     * @param int $pageIndex
     * @return self
     */
    public function setPageIndex(int $pageIndex): self
    {
        $this->data['page_index'] = $pageIndex;

        return $this;
    }

    /**
     * The current page range size (how many items per page)
     * 
     * Ex.: 20
     * 
     * @return int
     */
    public function getPageSize(): int
    {
        return $this->data['page_size'];
    }

    /**
     * @param int $pageSize
     * @return self
     */
    public function setPageSize(int $pageSize): self
    {
        $this->data['page_size'] = $pageSize;

        return $this;
    }

    /**
     * The current range size to show in selector (how many pages are visible in the page selector)
     * 
     * Ex. :
     * - 3 -> 4 5 6       (prev) (next) (current)
     * - 5 => 4 5 6 7 8
     * 
     * Best to use odd values !
     * 
     * @return int
     */
    public function getRangeSize(): int
    {
        return $this->data['range_size'];
    }

    /**
     * @param int $pageRange
     * @return self
     */
    public function setRangeSize(int $pageRange): self
    {
        $this->data['range_size'] = $pageRange;

        return $this;
    }

    /**
     * doctrine result values
     */

    public function getPageRecords(): int
    {
        return $this->data['page_records'];
    }

    public function setPageRecords(int $value): self
    {
        $this->data['page_records'] = $value;

        return $this;
    }

    public function getTotalRecords(): int
    {
        return $this->data['total_records'];
    }

    public function setTotalRecords(int $value): self
    {
        $this->data['total_records'] = $value;

        return $this;
    }

    public function getTotalPages(): int
    {
        return $this->data['total_pages'];
    }

    public function setTotalPages(int $value): self
    {
        $this->data['total_pages'] = $value;

        return $this;
    }

    /**
     * ui sort
     */

    /**
     * @return array
     */
    public function getSort(): array
    {
        return $this->data['sort'];
    }

    /**
     * @param array $sort
     * @return $this
     */
    public function setSort(array $sort): self
    {
        $this->data['sort'] = $sort;

        return $this;
    }

    public function addSort(string $field, string $direction): self
    {
        $this->data['sort'][$field] = $direction == 'desc' ? 'desc' : ($direction == 'asc' ? 'asc' : 'none');

        return $this;
    }

    public function removeSort(string $field): self
    {
        if (array_key_exists($field, $this->data['sort'])) {
            unset($this->data['sort'][$field]);
        }

        return $this;
    }

    public function resetSort(): self
    {
        $this->data['sort'] = [];

        return $this;
    }

    public function getSortAsString(): string
    {
        $a = [];

        foreach ($this->data['sort'] as $field => $direction) {
            $a[] = sprintf("%s".static::SORT_FIELD_DIRECTION_SEPARATOR."%s", $field, $direction);
        }

        return join(static::SORT_FIELDS_SEPARATOR, $a);
    }

    public function setSortFromString(string $sort): self
    {
        $this->resetSort();

        $a = explode(static::SORT_FIELDS_SEPARATOR, $sort);

        foreach($a as $item) {
            $items = explode(static::SORT_FIELD_DIRECTION_SEPARATOR, $item);

            if (is_array($items) && count($items) == 2) {
                $field = trim($items[0]);
                $direction = strtolower(trim($items[1]));

                $this->data['sort'][$field] = $direction == 'desc' ? 'desc' : ($direction == 'asc' ? 'asc' : 'none');
            }
        }

        return $this;
    }

    /**
     * @return string|null
     *
     * @deprecated
     */
    public function getSortField(): ?string
    {
        return $this->data['sort_field'];
    }

    /**
     * @param string|null $field
     * @return $this
     *
     * @deprecated
     */
    public function setSortField(?string $field): self
    {
        $this->data['sort_field'] = $field;

        return $this;
    }

    /**
     * @return string|null
     *
     * @deprecated
     */
    public function getSortDirection(): ?string
    {
        return $this->data['sort_direction'];
    }

    /**
     * @param string|null $sort
     * @return $this
     *
     * @deprecated
     */
    public function setSortDirection(?string $sort): self
    {
        $this->data['sort_direction'] = $sort;

        return $this;
    }

    /**
     * sql sort
     */

    /**
     * @return string|null
     *
     * @deprecated
     */
    public function getSqlSortField(): ?string
    {
        return $this->data['sqlSortField'];
    }

    /**
     * @param string|null $field
     * @return $this
     *
     * @deprecated
     */
    public function setSqlSortField(?string $field): self
    {
        $this->data['sqlSortField'] = $field;

        return $this;
    }

    /**
     * @return string|null
     *
     * @deprecated
     */
    public function getSqlSortDirection(): ?string
    {
        return $this->data['sqlSortDirection'];
    }

    /**
     * @param string|null $sort
     * @return $this
     *
     * @deprecated
     */
    public function setSqlSortDirection(?string $sort): self
    {
        $this->data['sqlSortDirection'] = $sort;

        return $this;
    }

    /**
     * Helper to switch to (and test) new data structure
     *
     * @return self
     */
    public function removeDeprecatedSortData(): self
    {
        unset($this->data['sort_field']);
        unset($this->data['sort_direction']);

        unset($this->data['sqlSort']);
        unset($this->data['sqlSortField']);
        unset($this->data['sqlSortDirection']);

        return $this;
    }

    /**
     * sql search
     */


    public function getSqlSearchString(): ?string
    {
        return $this->data['sqlSearchString'];
    }

    public function setSqlSearchString(?string $s): self
    {
        $this->data['sqlSearchString'] = $s;

        return $this;
    }

    /**
     * @return array
     * @deprecated
     */
    public function getSqlFilter(): array
    {
        return $this->data['sqlFilter'];
    }

    /**
     * @param array $filter
     * @return self
     * @deprecated
     */
    public function setSqlFilter(array $filter): self
    {
        $this->data['sqlFilter'] = $filter;

        return $this;
    }

    /**
     * get calculated pagination data values
     */

    public function resetPaginationData(): void
    {
        $this->data['pages'] = null;

        $this->data['paginationRangeStartPage'] = 0;
        $this->data['paginationRangeEndPage'] = 0;
        $this->data['paginationRangeIncludesFirst'] = true;
        $this->data['paginationRangeIncludesLast'] = true;
    }

    public function getPages(): ?array
    {
        $this->calculatePaginationData();

        return $this->data['pages'];
    }

    public function getPaginationRangeStartPage(): ?int
    {
        $this->calculatePaginationData();

        return $this->data['paginationRangeStartPage'];
    }

    public function getPaginationRangeEndPage(): ?int
    {
        $this->calculatePaginationData();

        return $this->data['paginationRangeEndPage'];
    }

    public function paginationRangeIncludesFirst(): ?bool
    {
        $this->calculatePaginationData();

        return $this->data['paginationRangeIncludesFirst'];
    }

    public function paginationRangeIncludesLast(): ?bool
    {
        $this->calculatePaginationData();

        return $this->data['paginationRangeIncludesLast'];
    }

    /* get pagination from data */

    /**
     * Calculate some 'geometry' pagination data, after doctrine paginator has loaded the entities !
     *
     * @return void
     * @throws \Exception
     */
    private function calculatePaginationData(): void
    {
        // already calculated ?
        if (!empty($this->data['pages'])) {
            return;
        }
       
        // data passed from doctrine query

        //$pageRecords = $this->getPageRecords();
        $totalRecords = $this->getTotalRecords();
        $totalPages = $this->getTotalPages();

        // data from configuration
        $pageIndex = $this->getPageIndex(); // int : current page index
        $rangeSize = $this->getRangeSize(); // int : current range size
        
        if ($totalRecords == 0) {
            $this->data['pages'] = array(0);

            return;
        }

        if ($rangeSize < 3) {
            throw new \Exception('PaginationData.rangeSize too small');
        }

        // calculation

        if ($rangeSize % 2 <> 0) {
            $halfRangeSize = intval(($rangeSize - 1) / 2);
        } else {
            $halfRangeSize = intval($rangeSize / 2);
        }

        $paginationRangeStartPage = $pageIndex - $halfRangeSize < 0 ? 0 : $pageIndex - $halfRangeSize;
        $paginationRangeEndPage = $pageIndex + $halfRangeSize > $totalPages - 1 ? $totalPages - 1 : $pageIndex + $halfRangeSize;

        if ($paginationRangeStartPage < 0) {
            throw new \Exception('PaginationData.RangeStart too small');
        }
        if ($paginationRangeStartPage > $paginationRangeEndPage) {
            throw new \Exception('PaginationData.RangeEnd too small');
        }

        while (
            ($paginationRangeEndPage - $paginationRangeStartPage + 1 < $rangeSize &&
                $paginationRangeStartPage != 0) ||
            ($paginationRangeEndPage - $paginationRangeStartPage + 1 < $rangeSize &&
                $paginationRangeEndPage != $totalPages - 1)
            ) {

            while (
                $paginationRangeEndPage - $paginationRangeStartPage + 1 < $rangeSize &&
                $paginationRangeStartPage != 0
                ) {
                // prepend one
                $paginationRangeStartPage--;
            }

            while (
                $paginationRangeEndPage - $paginationRangeStartPage + 1 < $rangeSize &&
                $paginationRangeEndPage != $totalPages - 1
                ) {
                // append one
                $paginationRangeEndPage++;
            }

        }

        // update pagination 'geometry' data
        $this->data['paginationRangeStartPage'] = $paginationRangeStartPage;
        $this->data['paginationRangeEndPage'] = $paginationRangeEndPage;
        $this->data['paginationRangeIncludesFirst'] = $paginationRangeStartPage == 0;
        $this->data['paginationRangeIncludesLast'] = $paginationRangeEndPage == ($totalPages - 1);

        $this->data['pages'] = array();
        for ($i = $paginationRangeStartPage; $i <= $paginationRangeEndPage; $i++) {
            $this->data['pages'][] = $i;
        }
    }
}
