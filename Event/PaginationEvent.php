<?php


namespace ITE\FiltrationBundle\Event;

use ITE\FiltrationBundle\Filtration\Filter\AbstractFilter;
use ITE\FiltrationBundle\Filtration\FilterInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Class PaginationEvent
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class PaginationEvent extends FiltrationEvent
{
    /**
     * @var AbstractFilter
     */
    private $filter;

    /**
     * @var int
     */
    private $count = 0;

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

    /**
     * Get count
     *
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set count
     *
     * @param int $count
     *
     * @return PaginationEvent
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }
}
