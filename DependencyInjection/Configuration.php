<?php

namespace ITE\FiltrationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ite_filtration');

        $rootNode
            ->children()
                ->arrayNode('filtration')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('filters')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('default')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('template_name')->defaultValue('@ITEFiltrationBundle/Resources/views/Filter/raw_form.html.twig')->end()
                                        ->variableNode('options')->defaultValue([
                                            'translation_domain' => 'ITEFiltrationBundle'
                                        ])->end()
                                    ->end()
                                ->end()
                                ->arrayNode('table')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('template_name')->defaultValue('@ITEFiltrationBundle/Resources/views/Filter/table.html.twig')->end()
                                        ->variableNode('options')->defaultValue([
                                            'translation_domain' => 'ITEFiltrationBundle',
                                            'table_class' => 'table',
                                            'table_attr' => [],
                                            'row_class' => '',
                                            'row_attr' => [],
                                            'cell_class' => '',
                                            'cell_attr' => [],
                                            'header_cell_class' => '',
                                            'header_cell_attr' => [],
                                            'filter_header' => [
                                                'wrapper_class' => '',
                                                'link_class' => 'btn btn-link',
                                                'span_class' => 'btn btn-link',
                                                'button_active_class' => 'active',
                                                'icon' => '',
                                                'list_class' => '',
                                                'list_item_class' => '',
                                                'field_wrapper_class' => 'row form-horizontal'
                                            ]
                                        ])->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('sorting')
                    ->canBeEnabled()
                ->end()
                ->scalarNode('disable_knp_sorting')
                    ->defaultTrue()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
