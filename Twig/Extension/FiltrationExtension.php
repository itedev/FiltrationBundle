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
    function __construct(FiltrationManager $filtrator)
    {
        $this->filtrator = $filtrator;
    }

    public function getFunctions()
    {
        return [
          new \Twig_SimpleFunction(
            'ite_filtration_render',
            array($this, 'render'),
            array('pre_escape' => 'html', 'is_safe' => array('html'), 'needs_environment' => true)
          ),
        ];
    }

    /**
     * @param \Twig_Environment $twig
     * @return string
     */
    public function render(\Twig_Environment $twig, $filterName)
    {
        $filter = $this->filtrator->getFilter($filterName);

        return $twig->render($filter->getTemplateName(), [
              'form' => $this->filtrator->getFilterForm($filterName)->createView(),
              'filter' => $filter
          ]);
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