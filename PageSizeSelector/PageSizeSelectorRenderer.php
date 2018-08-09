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

use jonasarts\Bundle\PaginationBundle\PageSizeSelector\PageSizeSelector;
use jonasarts\Bundle\PaginationBundle\Pagination\PaginationData;

/**
 * PageSizeSelectorRenderer class.
 */
class PageSizeSelectorRenderer
{
    // twig template engine
    private $twig;

    // default pagination template
    private $template = 'pagination/pagesize.html.twig';

    /**
     * Constructor.
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'use getPageSizeSelector() method';
    }

    /**
     * Override template on the fly.
     * 
     * @param string $template
     * @return self
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @param PaginationData $paginationData
     * @param array          $additionalData
     * @return Closure
     */
    public function getPageSizeSelector(PaginationData $paginationData, array $additionalData = null)
    {
        if (is_null($additionalData)) {
            $additionalData = array();
        }

        $pagesizeselector = new PageSizeSelector();

        $pagesizeselector->setSizes($paginationData->getSizes());
        $pagesizeselector->setCurrentSize($paginationData->getPageSize());

        $twig_env = $this->twig;
        $twig_template = $this->template;

        $pagesizeselector->renderer = function ($data) use ($twig_env, $twig_template, $additionalData) {
            //return var_export($data, true);
            
            $data = array('pagination' => $data);
            
            try {
                return $twig_env->render($twig_template, array_merge($data, $additionalData));
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        };

        return $pagesizeselector;
    }
}
