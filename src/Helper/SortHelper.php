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
 * SortHelper
 */
abstract class SortHelper
{
    public static function updateInfoFromRequest(Request $request, UserEntityIdInterface $user, AbstractRegistry $r, string $key, PaginationData &$paginationData): void
    {
        // GET
        $sort = $request->query->get('sort');

        // GET 2
        if (empty($sort)) {
            $f = $request->query->all('f'); // will return an empty array
            if (!empty($f) && array_key_exists('sort', $f)) {
                $sort = $f['sort'];
            }
        }

        if (!empty($sort)) {
            // save
            $r->rw($user->getId(), $key, 'sort', 's', trim((string)$sort));
        } else {
            // try to load - or use existing value as default
            $sort = $r->rrd($user->getId(), $key, 'sort', 's', $paginationData->getSortAsString());
        }

        $paginationData->setSortFromString($sort);
    }
}