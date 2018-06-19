<?php

namespace ITE\FiltrationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class FormatterPass
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class FormatterPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('ite_filtration.formatter.manager')) {
            return;
        }

        $definition = $container->getDefinition('ite_filtration.formatter.manager');

        $formatterProviders = $container->findTaggedServiceIds('ite_filtration.formatter_provider');
        foreach ($formatterProviders as $id => $tags) {
            $definition->addMethodCall('addFormatterProvider', [new Reference($id)]);
        }
    }
}
