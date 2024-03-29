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

namespace jonasarts\Bundle\PaginationBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PaginationExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration($container->getParameter('kernel.debug'));
        $config = $this->processConfiguration($configuration, $configs);

        // apply globals config
        $container->setParameter('pagination.globals.auto_register', $config['globals']['auto_register']);
        // apply paginator config
        $container->setParameter('pagination.paginator.template', $config['paginator']['template']);
        // apply pagesizer config
        $container->setParameter('pagination.pagesizer.template', $config['pagesizer']['template']);
        // apply counter config
        $container->setParameter('pagination.counter.template', $config['counter']['template']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');
    }

    /**
     * Define a custom bundle_alias
     *
     * {@inheritdoc}
     */
    public function getAlias(): string
    {
        return 'pagination';
    }
}
