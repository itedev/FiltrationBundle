<?php


namespace ITE\FiltrationBundle\Filtration\Filter;

/**
 * Class TableFilter
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
abstract class TableFilter extends AbstractFilter
{
    public function getTemplateName()
    {
        return '@ITEFiltrationBundle/Resources/views/Filter/table_filter.html.twig';
    }

} 