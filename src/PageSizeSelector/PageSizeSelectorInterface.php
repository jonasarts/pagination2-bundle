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

/**
 * PageSizeSelector interface.
 */
interface PageSizeSelectorInterface
{
    /**
     * @return array
     */
    public function getSizes(): array;

    /**
     * @param array $sizes
     */
    public function setSizes(array $sizes): self;

    /**
     * For which size currently?
     *
     * @return int
     */
    public function getCurrentSize(): int;

    /**
     * For which size currently?
     *
     * @param int $size
     */
    public function setCurrentSize(int $size): self;
}
