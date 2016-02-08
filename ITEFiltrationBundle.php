<?php

namespace ITE\FiltrationBundle;

use ITE\FiltrationBundle\DependencyInjection\Compiler\FiltrationPass;
use ITE\FiltrationBundle\DependencyInjection\Compiler\FormatterPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ITEFiltrationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new FiltrationPass());
        $container->addCompilerPass(new FormatterPass());
    }

}
