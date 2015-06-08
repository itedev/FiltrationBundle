<?php


namespace ITE\FiltrationBundle\Event;

/**
 * Class FiltrationEvents
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class FiltrationEvents
{
    const BEFORE_FILTER     = 'ite_filtration.before_filter';
    const FILTER            = 'ite_filtration.filter';
    const AFTER_FILTER      = 'ite_filtration.after_filter';
    const BEFORE_SORT       = 'ite_filtration.berfore_sort';
    const SORT              = 'ite_filtration.sort';
    const AFTER_SORT        = 'ite_filtration.aftersort';
}