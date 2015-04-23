<?php


namespace ITE\FiltrationBundle\Filtration\Filter;


use ITE\FiltrationBundle\Filtration\FilterInterface;

/**
 * Class AbstractFilter
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
abstract class AbstractFilter implements FilterInterface
{
    /**
     * @return string
     */
    public function getTemplateName()
    {
        return 'ITEFiltration:Filter:raw_form.html.twig';
    }

} 