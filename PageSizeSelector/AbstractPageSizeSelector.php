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

use jonasarts\Bundle\PaginationBundle\Interfaces\PageSizeSelectorInterface;

/**
 * AbstractPageSizeSelector class.
 */
abstract class AbstractPageSizeSelector implements PageSizeSelectorInterface
{
    private $currentSize;

    /**
     * 
     */
    public function getCurrentSize()
    {
        return $this->currentSize;
    }

    /**
     * 
     */
    public function setCurrentSize($size)
    {
        $this->currentSize = $size;

        return $this;
    }
}
