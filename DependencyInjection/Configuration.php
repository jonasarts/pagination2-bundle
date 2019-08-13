<?php

/*
 * This file is part of the Pagination bundle package.
 *
 * (c) Jonas Hauser <symfony@jonasarts.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace jonasarts\Bundle\PaginationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @var bool
     */
    private $debug;

    /**
     * Constructor
     *
     * @param Boolean $debug Whether to use the debug mode
     */
    public function __construct($debug)
    {
        $this->debug = (Boolean) $debug;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('pagination');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('globals')
                    ->addDefaultsIfNotSet()
                    ->children()
                        // auto register pagination values (requires registry service)
                        ->booleanNode('auto_register')
                            ->isRequired()
                            ->defaultTrue()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('paginator')
                    ->addDefaultsIfNotSet()
                    ->children()
                        // default template
                        ->scalarNode('template')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->defaultValue('pagination/sliding.html.twig')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('pagesizer')
                    ->addDefaultsIfNotSet()
                    ->children()
                        // default template
                        ->scalarNode('template')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->defaultValue('pagination/pagesize.html.twig')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('counter')
                    ->addDefaultsIfNotSet()
                    ->children()
                        // default template
                        ->scalarNode('template')
                            ->isRequired()
                            ->cannotBeEmpty()
                            ->defaultValue('pagination/counter.html.twig')
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
