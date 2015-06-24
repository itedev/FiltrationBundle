<?php


namespace ITE\FiltrationBundle\Filtration;

use ITE\FiltrationBundle\Filtration\Handler\HandlerInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Interface FiltrationInterface
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
interface FiltrationInterface
{
    /**
     * Filter given target by given filter
     *
     * @param mixed                  $target
     * @param string|FilterInterface $filter
     * @param array                  $options
     * @return mixed
     */
    public function filter($target, $filter, array $options = []);

    /**
     * Return filter by given name
     *
     * @param $name
     * @return FilterInterface
     */
    public function getFilter($name);

    /**
     * Return filter form by given filter name
     *
     * @param       $name
     * @param array $options
     * @return FormInterface
     */
    public function getFilterForm($name, $options = []);

    /**
     * Adds filter using Symfony DI
     *
     * @param FilterInterface $filter
     */
    public function addFilter(FilterInterface $filter);

    /**
     * Adds handler using Symfony DI
     *
     * @param HandlerInterface $handler
     */
    public function addHandler(HandlerInterface $handler);

    /**
     * Return all known filters
     *
     * @return FilterInterface[]
     */
    public function getFilters();
}