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
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        if (!$definition = $container->getDefinition('ite_filtration.manager')) {
            return;
        }

        $filtrators = $container->findTaggedServiceIds('ite_filtration.filtrator');

        foreach ($filtrators as $id => $tags) {
            $definition->addMethodCall('addFiltrator', [new Reference($id)]);
        }

        $filters = $container->findTaggedServiceIds('ite_filtration.filter');

        foreach ($filters as $id => $tags) {
            $definition->addMethodCall('addFilter', [new Reference($id)]);
        }
    }

} 