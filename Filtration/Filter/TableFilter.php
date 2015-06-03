<?php

namespace ITE\FiltrationBundle\Filtration\Filter;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TableFilter
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
abstract class TableFilter extends AbstractFilter
{
    /**
     * @var array
     */
    protected $defaultOptions = [
        'translation_domain' => 'ITEFiltrationBundle',
        'paginate' => false,
        'pagination_options' => [
          'limit' => 10,
          'query_param' => 'page',
          'options' => []
        ],
        'table_class' => 'table',
        'table_attr' => [],
        'row_class' => '',
        'row_attr' => [],
        'cell_class' => '',
        'cell_attr' => [],
        'header_cell_class' => '',
        'header_cell_attr' => [],
        'filter_header' => [
            'wrapper_class' => '',
            'link_class' => 'btn btn-link',
            'span_class' => 'btn btn-link',
            'button_active_class' => 'active',
            'icon' => '',
            'list_class' => '',
            'list_item_class' => '',
            'field_wrapper_class' => 'row form-horizontal'
        ]
    ];

    /**
     * {@inheritdoc}
     */
    public function getTemplateName()
    {
        return '@ITEFiltrationBundle/Resources/views/Filter/Table/base.html.twig';
    }

    /**
     * @inheritdoc
     */
    public function getOptions(array $options = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults($this->defaultOptions);
        $this->setOptions($resolver);

        return array_replace_recursive($this->defaultOptions, $resolver->resolve($options));
    }


    /**
     * @return array
     */
    abstract public function getHeaders();

} 