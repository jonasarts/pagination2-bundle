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

use jonasarts\Bundle\PaginationBundle\PageSizeSelector\PageSizeSelector;
use jonasarts\Bundle\PaginationBundle\Pagination\PaginationData;

/**
 * PageSizeSelectorRenderer class.
 */
class PageSizeSelectorRenderer
{
    // twig template engine
    private \Twig\Environment $twig;

    // default pagination template
    private string $template = 'pagination/pagesize.html.twig';

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
        return 'use getPageSizeSelector() method';
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
     * @param PaginationData $paginationData
     * @param array|null $additionalData
     * @return PageSizeSelector
     */
    public function getPageSizeSelector(PaginationData $paginationData, array $additionalData = null): PageSizeSelector
    {
        if (is_null($additionalData)) {
            $additionalData = array();
        }

        $pagesizeselector = new PageSizeSelector();

        $pagesizeselector->setSizes($paginationData->getPageSizes());
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
