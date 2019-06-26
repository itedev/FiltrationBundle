<?php


namespace ITE\FiltrationBundle\Twig\Extension;

use Doctrine\ORM\QueryBuilder;
use ITE\FiltrationBundle\Filtration\FiltrationManager;
use ITE\FiltrationBundle\Filtration\Result\FiltrationResultInterface;
use ITE\FiltrationBundle\Filtration\Templating\FormatterInterface;
use ITE\FiltrationBundle\Filtration\Templating\FormatterManager;
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
     * @var FormatterManager $formatterManager
     */
    protected $formatterManager;

    /**
     * @var UrlGenerator
     */
    protected $urlGenerator;

    /**
     * FiltrationExtension constructor.
     *
     * @param FiltrationManager $filtrator
     * @param FormatterManager $formatterManager
     * @param UrlGenerator|null $urlGenerator
     */
    public function __construct(FiltrationManager $filtrator, FormatterManager $formatterManager, UrlGenerator $urlGenerator = null)
    {
        $this->filtrator = $filtrator;
        $this->formatterManager = $formatterManager;
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
    public function render(\Twig_Environment $twig, $filterName, array $context = [], $recreateForm = false)
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
            'form' => $this->getFilterForm($filterName, [], $recreateForm),
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
        if (!isset($context['form'][$fieldName])) {
            throw new \Exception(sprintf('Field with name "%s" is not found in form for auto formatting.', $fieldName));
        }
        $field = $context['form'][$fieldName];
        $formatterName = $field->vars['filter_formatter'];
        $formatterParams = $field->vars['filter_formatter_params'];

        $filterFieldName = $field->vars['filter_field'];
        $value = $this->getValue($item, $filterFieldName ? $filterFieldName : $fieldName);

        $formatter = $this->formatterManager->getFormatter($formatterName);

        $params = [];

        if ($formatter->getOptions()['needs_context']) {
            $params[] = $context;
        }
        if ($formatter->getOptions()['needs_data']) {
            $params[] = $item;
        }

        $params = array_merge($params, $formatterParams);

        return $this->formatterManager->format($value, $formatterName, $params);
    }

    /**
     * @param $item
     * @param $fieldName
     *
     * @return mixed
     */
    private function getValue($item, $fieldName)
    {
        $value = null;

        if (is_array($item)) {
            if (array_key_exists($fieldName, $item)) {
                return $item[$fieldName];
            }

            if (!isset($item[0])) {
                return null;
            }

            $item = $item[0];
        }
        $accessor = PropertyAccess::createPropertyAccessor();

        try {
            $value = $accessor->getValue($item, $fieldName);
        }
        catch (\Exception $e) {

        }

        return $value;
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
    public function getFilterForm($name, $options = [], $recreate = false)
    {
        return $this->filtrator->getFilterForm($name, $options, $recreate)->createView();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ite_filtration.twig.extension.filtration';
    }

} 