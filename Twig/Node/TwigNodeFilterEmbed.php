<?php


namespace ITE\FiltrationBundle\Twig\Node;

use Twig_Compiler;
use Twig_Node_Expression;

/**
 * Class TwigNodeFilterEmbed
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class TwigNodeFilterEmbed extends \Twig_Node_Embed
{
    /**
     * @var Twig_Node_Expression
     */
    private $filterName;

    public function __construct(
        $filename,
        $index,
        $filterName,
        Twig_Node_Expression $variables = null,
        $only = false,
        $ignoreMissing = false,
        $lineno,
        $tag = null
    ) {
        parent::__construct(
            $filename,
            $index,
            $variables,
            $only,
            $ignoreMissing,
            $lineno,
            $tag
        );
        $this->filterName = $filterName;
    }

    public function compile(Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this);

        $compiler->raw('$context[\'filter\'] = $this->env->getExtension(\'ite_filtration.twig.extension.filtration\')->getFilter(\''.$this->filterName.'\');'."\n");
        $compiler->raw('$context[\'form\'] = $this->env->getExtension(\'ite_filtration.twig.extension.filtration\')->getFilterForm(\''.$this->filterName.'\');'."\n");

        parent::compile($compiler);
    }

}