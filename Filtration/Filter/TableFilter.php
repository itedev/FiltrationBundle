<?php


namespace ITE\FiltrationBundle\Filtration\Filter;

/**
 * Class TableFilter
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
abstract class TableFilter extends AbstractFilter
{
    protected $attributes = [
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
            'button_class' => 'btn btn-link',
            'button_active_class' => 'active',
            'icon' => '',
            'list_class' => '',
            'list_item_class' => '',
            'field_wrapper_class' => 'row form-horizontal'
        ]
    ];

    public function getTemplateName()
    {
        return '@ITEFiltrationBundle/Resources/views/Filter/Table/base.html.twig';
    }

    public function getBodyTemplate()
    {
        return '@ITEFiltrationBundle/Resources/views/Filter/Table/body.html.twig';
    }

    public function getRowTemplate()
    {
        return '@ITEFiltrationBundle/Resources/views/Filter/Table/row.html.twig';
    }

    public function getCellTemplate()
    {
        return '@ITEFiltrationBundle/Resources/views/Filter/Table/cell.html.twig';
    }

    public function getFilterHeaderTemplate()
    {
        return '@ITEFiltrationBundle/Resources/views/Filter/Table/filter_header.html.twig';
    }

    public function getRawHeaderTemplate()
    {
        return '@ITEFiltrationBundle/Resources/views/Filter/Table/raw_header.html.twig';
    }

    /**
     * @return array
     */
    public function getAttributes() {
        $this->attributes = array_replace_recursive($this->attributes, $this->setAttributes($this->attributes));
        return $this->attributes;
    }

    /**
     * @return array
     */
    abstract public function getHeaders();

    /**
     * @param $attributes
     * @return array
     */
    abstract protected function setAttributes($attributes);

} 