<?php

namespace ITE\FiltrationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class FiltrationPass
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class FiltrationPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('ite_filtration.manager')) {
            return;
        }

        $definition = $container->getDefinition('ite_filtration.manager');

        $filtrators = $container->findTaggedServiceIds('ite_filtration.filtrator');
        foreach ($filtrators as $id => $tags) {
            $definition->addMethodCall('addFiltrator', [new Reference($id)]);
        }

        $filters = $container->findTaggedServiceIds('ite_filtration.filter');
        foreach ($filters as $id => $tags) {
            $definition->addMethodCall('addFilter', [new Reference($id)]);
        }

        $handlers = $container->findTaggedServiceIds('ite_filtration.handler');
        foreach ($handlers as $id => $tags) {
            $definition->addMethodCall('addHandler', [new Reference($id)]);
        }
    }

} 