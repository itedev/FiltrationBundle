<?php


namespace ITE\FiltrationBundle\Twig\Extension;

use ITE\FiltrationBundle\Filtration\FiltrationManager;
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
    private $filtrator;

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
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'ite_filtration_render',
                [$this, 'render'],
                ['pre_escape' => 'html', 'is_safe' => ['html'], 'needs_environment' => true]
            ),
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
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'ite_filtration';
    }

} 