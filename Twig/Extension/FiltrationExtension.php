<?php


namespace ITE\FiltrationBundle\Twig\Extension;

use Doctrine\ORM\QueryBuilder;
use ITE\FiltrationBundle\Filtration\FiltrationManager;
use ITE\FiltrationBundle\Sorting\UrlGenerator;
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
     * @var UrlGenerator
     */
    private $urlGenerator;

    /**
     * @param FiltrationManager $filtrator
     */
    public function __construct(FiltrationManager $filtrator, UrlGenerator $urlGenerator = null)
    {
        $this->filtrator = $filtrator;
        $this->urlGenerator = $urlGenerator;
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

    public function getGlobals()
    {
        return [
            'ite_filtration_url_generator' => $this->urlGenerator
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
        $filter = $this->getFilter($filterName);

//        if (!isset($context['target'])) {
//            throw new \InvalidArgumentException('You need to pass "target" parameter into filter.');
//        }

        if ($context['target'] instanceof QueryBuilder) {
            $context['target'] = $context['target']->getQuery()->getResult();
        }

        $context = array_merge($context, [
            'form' => $this->getFilterForm($filterName),
            'filter' => $filter
        ]);

        return $twig->render($filter->getTemplateName(), $context);
    }

    /**
     * @param $name
     * @return \ITE\FiltrationBundle\Filtration\FilterInterface
     */
    public function getFilter($name)
    {
        return $this->filtrator->getFilter($name);
    }

    /**
     * @param $name
     * @return \Symfony\Component\Form\FormView
     */
    public function getFilterForm($name)
    {
        return $this->filtrator->getFilterForm($name)->createView();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ite_filtration.twig.extension.filtration';
    }

} 