<?php


namespace ITE\FiltrationBundle\Event;

/**
 * Class FiltrationEvents
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class FiltrationEvents
{
    // filtration
    const INIT_FILTER       = 'ite_filtration.init_filter';
    const BEFORE_FILTER     = 'ite_filtration.before_filter';
    const FILTER            = 'ite_filtration.filter';
    const AFTER_FILTER      = 'ite_filtration.after_filter';

    // sorting
    const BEFORE_SORT       = 'ite_filtration.berfore_sort';
    const SORT              = 'ite_filtration.sort';
    const AFTER_SORT        = 'ite_filtration.after_sort';

    // pagination
    const BEFORE_PAGINATE       = 'ite_filtration.berfore_paginate';
    const PAGINATE              = 'ite_filtration.paginate';
    const AFTER_PAGINATE        = 'ite_filtration.after_paginate';
}