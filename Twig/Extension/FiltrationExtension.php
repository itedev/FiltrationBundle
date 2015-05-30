<?php


namespace ITE\FiltrationBundle\Twig\Extension;

use ITE\FiltrationBundle\Filtration\FiltrationManager;
use ITE\FiltrationBundle\Twig\TokenParser\FilterEmbedTokenParser;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class FiltrationExtension
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class FiltrationExtension extends \Twig_Extension
{
    /**
     * @var FiltrationManager
     */
    protected $filtrator;

    /**
     * @param FiltrationManager $filtrator
     */
    public function __construct(FiltrationManager $filtrator)
    {
        $this->filtrator = $filtrator;
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenParsers()
    {
        return [
            new FilterEmbedTokenParser($this->filtrator)
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('ite_filtration_render', [$this, 'render'], [
                'pre_escape' => 'html', 
                'is_safe' => ['html'], 
                'needs_environment' => true
            ]),
            new \Twig_SimpleFunction('ite_is_block_exists', [$this, 'isBlockExists'], [
                'pre_escape' => 'html', 
                'is_safe' => ['html'], 
                'needs_environment' => true
            ])
        ];
    }

    /**
     * @param \Twig_Environment $twig
     * @param                   $filterName
     * @param array             $context
     * @return string
     */
    public function render(\Twig_Environment $twig, $filterName, array $context = [])
    {
        $filter = $this->filtrator->getFilter($filterName);

        $context = array_merge($context, [
            'form' => $this->filtrator->getFilterForm($filterName)->createView(),
            'filter' => $filter
        ]);

        return $twig->render($filter->getTemplateName(), $context);
    }

    /**
     * @param \Twig_Environment $twig
     * @param                   $templateName
     * @param                   $blockName
     * @return bool
     */
    public function isBlockExists(\Twig_Environment $twig, $templateName, $blockName)
    {
        return $twig->resolveTemplate($templateName)->hasBlock($blockName);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ite_filtration.twig.extension.filtration';
    }

} 