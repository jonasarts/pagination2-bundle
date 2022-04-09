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

namespace jonasarts\Bundle\PaginationBundle\PageSizeSelector;

use jonasarts\Bundle\PaginationBundle\PageSizeSelector\PageSizeSelectorInterface;

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
     * @var int
     */
    private $currentSize;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->sizes = array(10,20,50,100);
    }

    /**
     * @return array
     */
    public function getSizes(): array
    {
        return $this->sizes;
    }

    /**
     * @param array $sizes
     * @return self
     */
    public function setSizes(array $sizes): self
    {
        $this->sizes = $sizes;

        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentSize(): int
    {
        return $this->currentSize;
    }

    /**
     * @param int $size
     * @return self
     */
    public function setCurrentSize(int $size): self
    {
        $this->currentSize = $size;

        return $this;
    }
}
