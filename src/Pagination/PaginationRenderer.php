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

namespace jonasarts\Bundle\PaginationBundle\Pagination;

use jonasarts\Bundle\PaginationBundle\Pagination\Pagination;
use jonasarts\Bundle\PaginationBundle\Pagination\PaginationData;

/**
 * PaginationRenderer class.
 */
class PaginationRenderer
{
    // twig template engine
    private \Twig\Environment $twig;

    // default pagination template
    private string $template = 'pagination/sliding.html.twig';

    /**
     * Constructor.
     */
    public function __construct(\Twig\Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return 'use getPagination() method';
    }

    /**
     * Override template on the fly.
     *
     * @param string $template
     * @return self
     */
    public function setTemplate(string $template): self
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @param array $entities
     * @param PaginationData $paginationData
     * @param array|null $additionalData
     * @return Pagination
     */
    public function getPagination(array $entities, PaginationData $paginationData, array $additionalData = null): Pagination
    {
        if (is_null($entities)) {
            $entities = array();
        }
        if (is_null($additionalData)) {
            $additionalData = array();
        }

        $pagination = new Pagination($entities, $paginationData->getTotalRecords());

        $pagination->setCurrentPage($paginationData->getPageIndex());
        $pagination->setPageRangeSize($paginationData->getRangeSize());
        $pagination->setPageSize($paginationData->getPageSize());

        $twig_env = $this->twig;
        $twig_template = $this->template;

        $pagination->renderer = function ($data) use ($twig_env, $twig_template, $additionalData) {
            //return var_export($data, true);

            $data = array('pagination' => $data);

            try {
                return $twig_env->render($twig_template, array_merge($data, $additionalData));
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        };

        return $pagination;
    }
}
