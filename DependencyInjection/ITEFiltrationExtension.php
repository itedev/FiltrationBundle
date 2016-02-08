<?php

namespace ITE\FiltrationBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ITEFiltrationExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $this->loadCoreServices($config, $container, $loader);
        $this->loadFiltration($config, $container, $loader);
        $this->loadSorting($config, $container, $loader);
        $this->loadPagination($config, $container, $loader);

        if (!$config['disable_knp_sorting']) {
            $container->removeDefinition('ite_filtration.knp.pager_sortable_disable_sorting.event_subscriber');
        }
        $container->setParameter('ite_filtration.pagination', $config['pagination']);
    }

    /**
     * @param array                 $config
     * @param ContainerBuilder      $container
     * @param Loader\YamlFileLoader $loader
     */
    protected function loadCoreServices(array $config, ContainerBuilder $container, Loader\YamlFileLoader $loader)
    {
        $loader->load('services.yml');
        $loader->load('filtrators.yml');
        $loader->load('handlers.yml');
        $loader->load('templating.yml');
    }

    /**
     * @param array                 $config
     * @param ContainerBuilder      $container
     * @param Loader\YamlFileLoader $loader
     */
    protected function loadFiltration(array $config, ContainerBuilder $container, Loader\YamlFileLoader $loader)
    {
        $loader->load('form_types.yml');
    }

    protected function loadSorting(array $config, ContainerBuilder $container, Loader\YamlFileLoader $loader)
    {
        $loader->load('sorting.yml');
    }

    protected function loadPagination(array $config, ContainerBuilder $container, Loader\YamlFileLoader $loader)
    {
        $loader->load('pagination.yml');
    }
}
