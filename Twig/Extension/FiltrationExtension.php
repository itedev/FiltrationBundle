<?php


namespace ITE\FiltrationBundle\Twig\Extension;

use Doctrine\ORM\QueryBuilder;
use ITE\FiltrationBundle\Filtration\FiltrationManager;
use ITE\FiltrationBundle\Filtration\Result\FiltrationResultInterface;
use ITE\FiltrationBundle\Filtration\Templating\FormatterInterface;
use ITE\FiltrationBundle\Filtration\Templating\FormatterProviderInterface;
use ITE\FiltrationBundle\Twig\TokenParser\FilterEmbedTokenParser;
use ITE\FiltrationBundle\Util\UrlGenerator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyAccess\PropertyAccess;

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
     * @var FormatterInterface[]
     */
    private $formatters = [];

    /**
     * @param FiltrationManager $filtrator
     */
    public function __construct(FiltrationManager $filtrator, UrlGenerator $urlGenerator = null)
    {
        $this->filtrator = $filtrator;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param FormatterProviderInterface $provider
     */
    public function addFormatterProvider(FormatterProviderInterface $provider)
    {
        foreach ($provider->getFormatters() as $formatter) {
            $this->formatters[$formatter->getName()] = $formatter;
        }
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
            new \Twig_SimpleFunction('ite_filtration_format_field', [$this, 'format'], [
                'pre_escape' => 'html',
                'is_safe' => ['html'],
                'needs_context' => true,
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
        if ($filterName instanceof FiltrationResultInterface) {
            return $twig->render($filterName->getFilter()->getTemplateName(), [
                'form' => $filterName->getFilterForm()->createView(),
                'filter' => $filterName->getFilter(),
                'target' => $filterName,
            ]);
        }
        $filter = $this->getFilter($filterName);

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
     * @param $context
     * @param $item
     * @param $fieldName
     *
     * @return mixed
     * @throws \Exception
     */
    public function format($context, $item, $fieldName)
    {
        /** @var FormView $field */
        $field = $context['form'][$fieldName];
        $formatter = $field->vars['filter_formatter'];
        if (!isset($this->formatters[$formatter])) {
            throw new \Exception(sprintf('Formatter "%s" is not registerd.'));
        }
        $formatter = $this->formatters[$formatter];
        $formatterParams = $field->vars['filter_formatter_params'];
        $value = $this->getValue($item, $fieldName);
        $params = [];
        if ($formatter->getOptions()['needs_context']) {
            $params[] = $context;
        }
        if ($formatter->getOptions()['needs_data']) {
            $params[] = $item;
        }
        $params = array_merge($params, [$value], $formatterParams);

        return call_user_func_array($formatter->getCallable(), $params);
    }

    /**
     * @param $item
     * @param $fieldName
     *
     * @return mixed
     */
    private function getValue($item, $fieldName)
    {
        if (is_array($item) && isset($item[$fieldName])) {
            return $item[$fieldName];
        } else {
            $item = $item[0];
        }
        $accessor = PropertyAccess::createPropertyAccessor();

        return $accessor->getValue($item, $fieldName);
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