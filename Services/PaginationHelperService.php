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

use Symfony\Component\DependencyInjection\ContainerInterface;
use jonasarts\Bundle\PaginationBundle\Counter\Counter;
use jonasarts\Bundle\PaginationBundle\PaginationData\PaginationData;

/**
 * PaginationHelperService class.
 */
class PaginationHelperService
{
    // service container
    private $container;
    // request
    private $request;
    // route name
    private $route;
    // user
    private $user;

    // twig template engine
    private $twig;

    // default pagination template
    private $template = 'pagination/counter.html.twig';

    /**
     * @var PaginationData
     */
    private $pagination_data;

    /**
     * Constructor.
     */
    public function __construct(ContainerInterface $container, \Twig_Environment $twig)
    {
        $this->container = $container;
        $this->request = $this->container->get('request');
        $this->route = $this->request->get('_route');
        $this->user = $this->container->get('security.token_storage')->getToken()->getUser();

        $this->twig = $twig;

        // get defaults form config.yml
        $page_range = 10;
        $page_size = 10;
        $page_parameter_name = 'page';
        $page_range_paramter_name = 'range';
        $page_size_paramter_name = 'size';
        $sort_field_name = 'sort';

        $this->pagination_data = new PaginationData();

        $this->pagination_data->setPageIndex($this->getPageIndex($page_parameter_name));
        $this->pagination_data->setPageRange($this->getPageRange($page_range_paramter_name, $page_range));
        $this->pagination_data->setPageSize($this->getPageSize($page_size_paramter_name, $page_size));
        $this->pagination_data->setSortFieldName($this->getSortFieldName($sort_field_name));
        $this->pagination_data->setSortDirection($this->getSortDirection());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'use getCounter() method';
    }

    /**
     * Override pagesize template on the fly.
     * 
     * @param string $template
     *
     * @return CounterService
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @param int $pageItemsCount
     * @param int $totalItemsCount
     *
     * @return CounterService
     */
    public function setItems($pageItemsCount, $totalItemsCount)
    {
        $this->pagination_data->setPageItemsCount($pageItemsCount);
        $this->pagination_data->setTotalItemsCount($totalItemsCount);

        return $this;
    }

    /**
     * @return PaginationData
     */
    public function getPaginationData()
    {
        return $this->pagination_data;
    }

    /**
     * @return Closure
     */
    public function getCounter()
    {
        $additionalData = array();

        $counter = new Counter();

        $counter->setPaginationData($this->pagination_data);

        $twig_env = $this->twig;
        $twig_template = $this->template;

        $counter->renderer = function ($data) use ($twig_env, $twig_template, $additionalData) {
            // return var_export($data, true);
            // common errors to check: is $twig_template file present?
            // return $twig_template;

            try {
                return $twig_env->render($twig_template, array_merge($data, $additionalData));
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        };

        return $counter;
    }

    /**
     * Reads the page index.
     * 
     * @param string $name               The parameter name to read from request to get page_index value
     * @param int    $default_page_index The value to return if not custom page_index is found
     *
     * @return interger
     */
    private function getPageIndex($name = 'pageindex', $default_page_index = 1)
    {
        $page_index = null;

        if ($this->request->query->has($name)) {
            $page_index = $this->request->query->get($name);
        }

        $page_index = $this->register($name, 'i', $page_index);

        if ($page_index == 0 || $page_index == null) {
            $page_index = $default_page_index;
        }

        return $page_index;
    }

    /**
     * Reads the page range.
     * 
     * @param string $name               The parameter name to read from request to get page_range value
     * @param string $default_page_range The value to return if no custom page_range is found
     *
     * @return int
     */
    private function getPageRange($name = 'pagerange', $default_page_range = 10)
    {
        $page_range = null;

        if ($this->request->query->has($name)) {
            $page_range = $this->request->query->get($name);
        }

        $page_range = $this->register($name, 'i', $page_range);

        if ($page_range == 0 || $page_range == null) {
            $page_range = $default_page_range;
        }

        return $page_range;
    }

    /**
     * Reads the page size.
     * 
     * @param string $name              The parameter name to read from request to get page_size value
     * @param int    $default_page_size The value to return if no custom page_size is found
     *
     * @return int
     */
    private function getPageSize($name = 'pagesize', $default_page_size = 10)
    {
        $page_size = null;

        if ($this->request->query->has($name)) {
            $page_size = $this->request->query->get($name);
        }

        $page_size = $this->register($name, 'i', $page_size);

        if ($page_size == 0 || $page_size == null) {
            $page_size = $default_page_size;
        }

        return $page_size;
    }

    /**
     * Reads sort field.
     * 
     * If sort field present, updates value to registry.
     * If no sort field is found, tries to read last value from registry.
     * 
     * @param string $default_field_name
     *
     * @return string
     */
    public function getSortFieldName($default_field_name)
    {
        $sort_field_name = null;

        $sort = $this->request->query->get('sort');

        $sort_array = explode('.', $sort);
        if (is_array($sort_array)) {
            if (trim($sort_array[0]) != '') {
                $sort_field_name = $sort_array[0];
            }
        }

        $sort_field_name = $this->register('sortfield', 's', $sort_field_name);

        if (trim($sort_field_name) == '') {
            $sort_field_name = $default_field_name;
        }

        return $sort_field_name;
    }

    /**
     * Reads sort direction.
     * 
     * If sort direction is present, updates value to registry.
     * If no sort direction is found, tries to read last value from registry.
     * 
     * @param string $default_direction
     *
     * @return string
     */
    public function getSortDirection($default_direction = 'asc')
    {
        $sort_direction = null;

        $sort = $this->request->query->get('sort');

        $sort_array = explode('.', $sort);
        if (is_array($sort_array)) {
            if (count($sort_array) > 1) {
                $sort_direction = strtolower($sort_array[1]) == 'desc' ? 'desc' : 'asc';
            }
        }

        $sort_direction = $this->register('sortdirection', 's', $sort_direction);

        if (trim($sort_direction) == '') {
            $sort_direction = $default_direction;
        }

        return $sort_direction;
    }

    /**
     * Method handling the auto-registering of values.
     * 
     * @param string $name
     * @param string $type
     * @param mixed  $value
     *
     * @return mixed read value if value is null; write value if value is given
     */
    private function register($name, $type, $value = null)
    {
        // abort if no user present
        // anon. user is now a string (symfony 2.8)
        if (is_null($this->user) || is_array($this->user) || is_string($this->user)) {
            return;
        }

        // abort if no route present
        if (trim($this->route) == '') {
            return;
        }

        $user_id = $this->user->getId();
        $key = 'pagination/'.$this->route;

        // if $value is null = read / if $value is set = write
        if ($this->container->has('registry')) {
            $rm = $this->container->get('registry');

            if ($value != null) {
                $rm->RegistryWrite($user_id, $key, $name, $type, $value);
            } else {
                $value = $rm->RegistryRead($user_id, $key, $name, $type);
            }
        }

        return $value;
    }
}
