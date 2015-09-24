<?php

/*
 * This file is part of the jonasarts Pagination bundle package.
 *
 * (c) Jonas Hauser <symfony@jonasarts.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace jonasarts\Bundle\PaginationBundle\Services;

use jonasarts\Bundle\PaginationBundle\Pagination\Pagination;
use jonasarts\Bundle\PaginationBundle\PaginationData\PaginationData;

/**
 * PaginatorService class.
 */
class PaginatorService
{
    // twig template engine
    private $twig;

    // default pagination template
    private $template = 'pagination/sliding.html.twig';

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
        return 'use getPagination() method';
    }

    /**
     * Override pagination template on the fly.
     * 
     * @param string $template
     *
     * @return PaginatorService
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @param array          $entities
     * @param PaginationData $paginationData
     * @param array          $additionalData
     *
     * @return Closure
     */
    public function getPagination(array $entities, PaginationData $paginationData, array $additionalData = null)
    {
        if (is_null($entities)) {
            $entities = array();
        }
        if (is_null($additionalData)) {
            $additionalData = array();
        }

        $pagination = new Pagination($entities, $paginationData->getTotalItemsCount());

        $pagination->setCurrentPage($paginationData->getPageIndex());
        $pagination->setPageRange($paginationData->getPageRange());
        $pagination->setPageSize($paginationData->getPageSize());

        $twig_env = $this->twig;
        $twig_template = $this->template;

        $pagination->renderer = function ($data) use ($twig_env, $twig_template, $additionalData) {
            //return var_export($data, true);
            // common errors to check: is $twig_template file present?
            //return $twig_template;

            try {
                return $twig_env->render($twig_template, array_merge($data, $additionalData));
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        };

        return $pagination;
    }
}
