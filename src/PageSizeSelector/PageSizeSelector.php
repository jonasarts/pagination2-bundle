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

use Closure;

/**
 * PageSizeSelector class.
 */
class PageSizeSelector extends AbstractPageSizeSelector
{
    /**
     * Closure which is executed to render pagination.
     *
     * @var Closure
     */
    public $renderer;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Renders the pagination.
     *
     * @return string
     */
    public function __toString(): string
    {
        $data = $this->getData();

        $output = '';

        if (!$this->renderer instanceof Closure) {
            $output = 'add a renderer in order to render a template';
        } else {
            try {
                $output = call_user_func($this->renderer, $data);
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        return $output;
    }

    /**
     * Populates an pagination 'viewdata' array.
     *
     * @return array
     */
    private function getData(): array
    {
        $viewData = array(
            'pageSizes' => $this->getSizes(),
            'currentSize' => $this->getCurrentSize(),
        );

        return $viewData;
    }
}
