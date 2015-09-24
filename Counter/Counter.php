<?php

/*
 * This file is part of the jonasarts Pagination bundle package.
 *
 * (c) Jonas Hauser <symfony@jonasarts.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace jonasarts\Bundle\PaginationBundle\Counter;

use Closure;

/**
 * Counter class.
 */
class Counter extends AbstractCounter
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
    }

    /**
     * Renders the pagination.
     * 
     * @return string
     */
    public function __toString()
    {
        $data = $this->getData();

        $output = '';

        if (!$this->renderer instanceof Closure) {
            $output = 'add a renderer in order to render a template';
        } else {
            $output = call_user_func($this->renderer, $data);
        }

        return $output;
    }

    /**
     * Populates an counter 'viewdata' array.
     *  
     * @return array
     */
    private function getData()
    {
        $viewData = $this->getPaginationData()->asArray();

        return $viewData;
    }
}
