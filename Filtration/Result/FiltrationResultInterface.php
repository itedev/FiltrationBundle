<?php


namespace ITE\FiltrationBundle\Filtration\Result;

/**
 * Interface FiltrationResultInterface
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
interface FiltrationResultInterface extends \IteratorAggregate , \Countable, \ArrayAccess
{
    /**
     * @param $items
     * @return mixed
     */
    public function setItems($items);
    public function setOptions($options);
    public function getOption($name, $defeault = null);
    public function getOptions();

}