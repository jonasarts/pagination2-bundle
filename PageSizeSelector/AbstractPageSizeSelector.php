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
    /**
     * @var array
     */
    private $sizes;

    /**
     * @var integer
     */
    private $currentSize;

    /**
     * 
     */
    public function __construct()
    {
        $this->sizes = array(10,20,50,100);
    }

    /**
     * 
     */
    public function getSizes()
    {
        return $this->sizes;
    }

    /**
     * 
     */
    public function setSizes(array $sizes)
    {
        $this->sizes = $sizes;

        return $this;
    }

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
