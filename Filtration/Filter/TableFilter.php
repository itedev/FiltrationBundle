<?php

namespace ITE\FiltrationBundle\Filtration\Filter;

use Doctrine\ORM\Query;
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
    protected $availableHeaders = [];

    /**
     * TableFilter constructor.
     */
    public function __construct()
    {
        $this->availableHeaders = $this->getHeaders();
    }

    /**
     * @var array
     */
    protected $defaultOptions = [
        'translation_domain' => 'ITEFiltrationBundle',
        'wrap_result' => false,
        'paginate' => false,
        'force_result_return' => false,
        'limit' => 10,
        'page'  => 1,
        'hydrator' => Query::HYDRATE_OBJECT,
        'data'  => [],
        'pagination' => [
            'pageParameterName' => 'page',
            'wrap_queries' => false,
            'distinct' => false,
            'fetch_join_collection' => false,
            'page_range' => 5,
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
        return '@ITEFiltrationBundle/Resources/views/Filter/table.html.twig';
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
     * Get availableHeaders
     *
     * @return array
     */
    public function getAvailableHeaders()
    {
        return $this->availableHeaders;
    }

    /**
     * Set availableHeaders
     *
     * @param array $availableHeaders
     *
     * @return TableFilter
     */
    public function setAvailableHeaders(array $availableHeaders)
    {
        $this->availableHeaders = $availableHeaders;

        return $this;
    }

    /**
     * @return array
     */
    abstract public function getHeaders();
}
