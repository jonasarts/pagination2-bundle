<?php

/*
 * This file is part of the jonasarts Pagination bundle package.
 *
 * (c) Jonas Hauser <symfony@jonasarts.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace jonasarts\Bundle\PaginationBundle\PageSizeSelector;

/**
 * PageSizeSelector interface.
 */
interface PageSizeSelectorInterface
{
    /**
     * @return array
     */
    public function getSizes();

    /**
     * @param array $sizes
     */
    public function setSizes(array $sizes);

    /**
     * For which size currently?
     * 
     * @return int
     */
    public function getCurrentSize();

    /**
     * For which size currently?
     * 
     * @param int $size
     */
    public function setCurrentSize($size);
}
