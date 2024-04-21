<?php

declare(strict_types=1);

/*
 * This file is part of the jonasarts Global Helper classes.
 *
 * (c) Jonas Hauser <symfony@jonasarts.com>
 * (c) HASCH GmbH, Jonas Hauser <hauser@hasch.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace jonasarts\Bundle\PaginationBundle\Helper;

use Hasch\Security\Model\UserEntityIdInterface;
use jonasarts\Bundle\PaginationBundle\Pagination\PaginationData;
use jonasarts\Bundle\RegistryBundle\Registry\AbstractRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * PaginationHelper
 */
abstract class PaginationHelper
{
    // sync this constant with Framework FilterHelper class constant
    final public const PAGINATOR_SEARCH_NOTIFICATION_FLAG = 'hasch_filter_search';

    /**
     * Returns PaginationData with
     * - pageIndex : int (used by repository paginate() method)
     * - pageSize : int (used by repository paginate() method)
     * - rangeSize : int (used below in paginationFromInfo() method)
     *
     * @throws \Exception
     */
    public static function infoFromRequest(Request $request, UserEntityIdInterface $user, AbstractRegistry $r, string $key): PaginationData
    {
        // GET
        $pageIndex = $request->query->get('page');
        $pageSize = $request->query->get('pagesize');
        $rangeSize = $request->query->get('rangesize');

        // reset to first page if a filter/search was executed
        if ($request->attributes->has(static::PAGINATOR_SEARCH_NOTIFICATION_FLAG)) {
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

        $paginationData = new PaginationData();
        $paginationData->setPageIndex(empty($pageIndex) ? 0 : intval($pageIndex));
        $paginationData->setPageSize(empty($pageSize) ? 10 : intval($pageSize));
        $paginationData->setRangeSize(empty($rangeSize) ? 5 : intval($rangeSize));

        return $paginationData;
    }
}