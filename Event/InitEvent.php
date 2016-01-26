<?php


namespace ITE\FiltrationBundle\Event;

use ITE\FiltrationBundle\Filtration\Filter\AbstractFilter;
use ITE\FiltrationBundle\Filtration\FilterInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Class InitEvent
 *
 * @author jeka <jekamozg@mail.ru>
 */
class InitEvent extends FiltrationEvent
{
    /**
     * @var AbstractFilter
     */
    private $filter;

    public function __construct(FormInterface $form, $target, $options = [], FilterInterface $filter)
    {
        parent::__construct($form, $target, $options);
        $this->filter = $filter;
    }

    /**
     * Get filter
     *
     * @return AbstractFilter
     */
    public function getFilter()
    {
        return $this->filter;
    }

}