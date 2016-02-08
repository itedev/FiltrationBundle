<?php


namespace ITE\FiltrationBundle\Filtration\Templating;

/**
 * Interface FormatterInterface
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
interface FormatterInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return callable
     */
    public function getCallable();

    /**
     * @return array
     */
    public function getOptions();
}
