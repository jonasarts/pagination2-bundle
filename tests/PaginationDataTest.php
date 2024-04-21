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

namespace jonasarts\Bundle\RegistryBundle\Tests;

use jonasarts\Bundle\PaginationBundle\Pagination\PaginationData;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PaginationDataTest extends WebTestCase
{
    public function testSort()
    {
        $data = new PaginationData();
        //dump($data);

        $data->addSort("test_field", "asc");
        $this->assertEquals("asc", $data->getSort()["test_field"]);

        $s = $data->getSortAsString();
        $this->assertEquals("test_field.asc", $s);

        $data->setSortFromString("my_field_1.asc&other_field_2.desc");
        $data->addSort("third", "none_X");

        $this->assertEquals(3, count($data->getSort()));
        $this->assertEquals("asc", $data->getSort()["my_field_1"]);
        $this->assertEquals("desc", $data->getSort()["other_field_2"]);
        $this->assertEquals("none", $data->getSort()["third"]);

        $data->removeSort("other_field_2");
        $this->assertEquals(2, count($data->getSort()));
        $this->assertEquals("none", $data->getSort()["third"]);
    }

    public function testPaginationData()
    {
        $data = new PaginationData();
        //dump($data);

        $pages = $data->getPages();
        //dump($pages);
        $start = $data->getPaginationRangeStartPage();
        $end = $data->getPaginationRangeEndPage();
        $first = $data->paginationRangeIncludesFirst();
        $last = $data->paginationRangeIncludesLast();

        $this->assertNotNull($pages);
        $this->assertEquals(0, $start);
        $this->assertEquals(0, $end);
        $this->assertTrue($first);
        $this->assertTrue($last);

        // --
        $data->resetPaginationData();

        $data->setTotalRecords(100);
        $data->setTotalPages(10);
        $data->setPageIndex(5);
        $data->setPageSize(10);
        $data->setRangeSize(5);

        /*
         * - getPageIndex (get/set)
         * - getPageSize  (get/set)
         * - getRangeSize (get/set)
         */

        $pages = $data->getPages();
        //dump($pages);
        $start = $data->getPaginationRangeStartPage();
        $end = $data->getPaginationRangeEndPage();
        $first = $data->paginationRangeIncludesFirst();
        $last = $data->paginationRangeIncludesLast();

        $this->assertEquals(5, count($pages));
        $this->assertEquals(3, $start);
        $this->assertEquals(7, $end);
        $this->assertFalse($first);
        $this->assertFalse($last);
    }

    public function testPaginationDataFirst()
    {
        // 0-index !

        $data = new PaginationData();
        $data->setTotalRecords(100);
        $data->setTotalPages(10);
        $data->setPageIndex(2);
        $data->setPageSize(10);
        $data->setRangeSize(5);

        $pages = $data->getPages();
        //dump($pages);
        $start = $data->getPaginationRangeStartPage();
        $end = $data->getPaginationRangeEndPage();
        $first = $data->paginationRangeIncludesFirst();
        $last = $data->paginationRangeIncludesLast();

        $this->assertEquals(5, count($pages));
        $this->assertEquals(0, $start);
        $this->assertEquals(4, $end);
        $this->assertTrue($first);
        $this->assertFalse($last);
    }

    public function testPaginationDataLast()
    {
        // 0-index !

        $data = new PaginationData();
        $data->setTotalRecords(100);
        $data->setTotalPages(10);
        $data->setPageIndex(8);
        $data->setPageSize(10);
        $data->setRangeSize(5);

        $pages = $data->getPages();
        //dump($pages);
        $start = $data->getPaginationRangeStartPage();
        $end = $data->getPaginationRangeEndPage();
        $first = $data->paginationRangeIncludesFirst();
        $last = $data->paginationRangeIncludesLast();

        $this->assertEquals(5, count($pages));
        $this->assertEquals(5, $start);
        $this->assertEquals(9, $end);
        $this->assertFalse($first);
        $this->assertTrue($last);
    }
}