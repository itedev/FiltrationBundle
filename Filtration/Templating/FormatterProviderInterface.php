<?php


namespace ITE\FiltrationBundle\Filtration\Templating;

/**
 * Interface FormatterProviderInterface
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
interface FormatterProviderInterface
{
    /**
     * @return FormatterInterface[]
     */
    public function getFormatters();
}
