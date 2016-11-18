<?php


namespace ITE\FiltrationBundle\Filtration\Result;

use ITE\FiltrationBundle\Filtration\FilterInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Interface FiltrationResultInterface
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
interface FiltrationResultInterface extends \IteratorAggregate, \Countable, \ArrayAccess
{
    /**
     * @param int $count
     */
    public function setCount($count);

    /**
     * @return int
     */
    public function getCount();

    /**
     * @return array
     */
    public function getOptions();

    /**
     * @return mixed
     */
    public function getOriginalTarget();

    /**
     * @return mixed
     */
    public function getFilteredTarget();

    /**
     * @return mixed
     */
    public function getSortedTarget();

    /**
     * @return mixed
     */
    public function getPaginatedTarget();

    /**
     * @return FilterInterface
     */
    public function getFilter();

    /**
     * @return FormInterface
     */
    public function getFilterForm();
}
