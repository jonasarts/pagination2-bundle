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

use Symfony\Component\HttpFoundation\Request;

use Hasch\Framework\Helper\FilterHelper;
use Hasch\Security\Model\UserInterface;
use jonasarts\Bundle\RegistryBundle\Registry\AbstractRegistry;

/**
 * PaginationData, use for 
 * - configuration before request (request -> configuration)
 * - pass configuration to paginator (configuration -> doctrine)
 * - get additional geometry after loading entities (doctrine -> geometry)
 * 
 * Used on loading pagination configuration data from request : loadFromRequest()
 * -> pageIndex
 * -> pageSize
 * -> rangeSize
 * 
 * Avail configuration values (to set before passing AbstractEntityRepository.paginate())
 * - getPageSizes (get/set)
 * - getPageIndex (get/set)
 * - getPageSize  (get/set)
 * - getRangeSize (get/set)
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
    /**
     * @var array
     */
    private $data;

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

            'sort_field' => null,
            'sort_direction' => null,
        );

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

    /* pagination configuration values */

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
     * @param array $pageSize
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

    /* ui sort */

    public function getSortField(): ?string
    {
        return $this->data['sort_field'];
    }

    public function setSortField(?string $field): self
    {
        $this->data['sort_field'] = $field;

        return $this;
    }

    public function getSortDirection(): ?string
    {
        return $this->data['sort_direction'];
    }

    public function setSortDirection(?string $sort): self
    {
        $this->data['sort_direction'] = $sort;

        return $this;
    }

    /* sql sort */

    public function getSqlSortField(): ?string
    {
        return $this->data['sqlSortField'];
    }

    public function setSqlSortField(?string $field): self
    {
        $this->data['sqlSortField'] = $field;

        return $this;
    }

    public function getSqlSortDirection(): ?string
    {
        return $this->data['sqlSortDirection'];
    }

    public function setSqlSortDirection(?string $sort): self
    {
        $this->data['sqlSortDirection'] = $sort;

        return $this;
    }

    public function getSqlSearchString(): ?string
    {
        return $this->data['sqlSearchString'];
    }

    public function setSqlSearchString(?string $s): self
    {
        $this->data['sqlSearchString'] = $sort;

        return $this;
    }

    public function getSqlFilter(): array
    {
        return $this->data['sqlFilter'];
    }

    public function setSqlFilter(array $filter): self
    {
        $this->data['sqlFilter'] = $filter;

        return $this;
    }

    /* get calculated pagination data values */

    public function resetPaginationData()
    {
        $this->data['pages'] = null;

        $this->data['paginationRangeStartPage'] = 0;
        $this->data['paginationRangeEndPage'] = 0;
        $this->data['paginationRangeIncludesFirst'] = true;
        $this->data['paginationRangeIncludesLast'] = true;
    }

    public function getPages(): ?array
    {
        $this->calculatePagination();

        return $this->data['pages'];
    }

    public function getPaginationRangeStartPage(): ?int
    {
        $this->calculatePagination();

        return $this->data['paginationRangeStartPage'];
    }

    public function getPaginationRangeEndPage(): ?int
    {
        $this->calculatePagination();

        return $this->data['paginationRangeEndPage'];
    }

    public function paginationRangeIncludesFirst(): ?bool
    {
        $this->calculatePagination();

        return $this->data['paginationRangeIncludesFirst'];
    }

    public function paginationRangeIncludesLast(): ?bool
    {
        $this->calculatePagination();

        return $this->data['paginationRangeIncludesLast'];
    }

    /* get pagination from data */

    /**
     * Calculate some 'geometry' paginaton data, after doctrine paginator has loaded the entities !
     * 
     * @return void
     */
    private function calculatePaginationData()
    {
        // already calculated ?
        if (!empty($this->data['pages'])) {
            return;
        }

        if (empty($this->repositoryData())) {
            throw new \Exception('PaginationData is missing the doctrine repository data');
        }
        
        // data passed from doctrine query

        //$pageRecords = $this->repositoryData->getPageRecords();
        $totalRecords = $this->repositoryData->getTotalRecords();
        $totalPages = $this->repositoryData->getTotalPages();

        // data from 

        $pageIndex = $this->data['pageIndex']; // int : current page index
        $rangeSize = $this->data['rangeSize']; // int : current range size
        
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

        return true;
    }

    /* configuration data methods */

    /**
     * Helper method to load pagination configuration from request & registry
     */
    public function loadFromRequest(Request $request, UserInterface $user, string $key, AbstractRegistry $r): self
    {
        // GET
        $pageIndex = $request->query->get('page');
        $pageSize = $request->query->get('pagesize');
        $rangeSize = $request->query->get('rangesize');

        // reset to first page if a filter/search was executed
        if ($request->attributes->has(FilterHelper::PAGINATOR_SEARCH_NOTIFICATION_FLAG)) {
            $pageIndex = 0; // reset
        }

        if (!is_null($pageIndex)) {
            // save
            $r->rw($user->getId(), $key, 'pageindex', 'i', intval($pageIndex));
        } else {
            // try to load
            $pageIndex = $r->rr($user->getId(), $key, 'pageindex', 'i');
        }

        if (!is_null($pageSize)) {
            // save
            $r->rw($user->getId(), $key, 'pagesize', 'i', intval($pageSize));
        } else {
            // try to load
            $pageSize = $r->rr($user->getId(), $key, 'pagesize', 'i');
        }
        
        if (!is_null($rangeSize)) {
            // save
            $r->rw($user->getId(), $key, 'rangesize', 'i', intval($rangeSize));
        } else {
            // try to load
            $rangeSize = $r->rr($user->getId(), $key, 'rangesize', 'i');
        }

        // validate
        if (!empty($pageIndex) && intval($pageIndex) < 0) {
            $pageIndex = 0;
        }
        if (!empty($pageSize) && intval($pageSize) < 1) {
            $pageSize = 10;
        }
        if (!empty($rangeSize) && intval($rangeSize) < 3) {
            $rangeSize = 3;
        }

        // update pagination configuration data
        $this->setPageIndex( empty($pageIndex) ? 0 : intval($pageIndex) );
        $this->setPageSize( empty($pageSize) ? 10 : intval($pageSize) );
        $this->setRangeSize( empty($rangeSize) ? 5 : intval($rangeSize) );

        return $this;
    }

    

}
