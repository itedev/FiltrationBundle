<?php

namespace ITE\FiltrationBundle\Filtration\Result;

use ITE\FiltrationBundle\Filtration\FilterInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Class FiltrationResultTrait
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
trait FiltrationResultTrait
{
    /**
     * @var int
     */
    protected $count = 0;

    /**
     * @var FilterInterface
     */
    protected $filter;

    /**
     * @var FormInterface
     */
    protected $filterForm;

    /**
     * @var mixed
     */
    protected $originalTarget;

    /**
     * @var mixed
     */
    protected $filteredTarget;

    /**
     * @var mixed
     */
    protected $sortedTarget;

    /**
     * @var array
     */
    protected $paginatedTarget = [];

    /**
     * @var array
     */
    protected $options;


    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->paginatedTarget);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->paginatedTarget[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->paginatedTarget[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->paginatedTarget[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->paginatedTarget[$offset]);
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
     * @return FiltrationResult
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->paginatedTarget);
    }

    /**
     * Get items
     *
     * @return array
     */
    public function getItems()
    {
        return $this->paginatedTarget;
    }

    /**
     * Get filter
     *
     * @return FilterInterface
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * Get filterForm
     *
     * @return FormInterface
     */
    public function getFilterForm()
    {
        return $this->filterForm;
    }

    /**
     * Get originalTarget
     *
     * @return mixed
     */
    public function getOriginalTarget()
    {
        return $this->originalTarget;
    }

    /**
     * Get filteredTarget
     *
     * @return mixed
     */
    public function getFilteredTarget()
    {
        return $this->filteredTarget;
    }

    /**
     * Get sortedTarget
     *
     * @return mixed
     */
    public function getSortedTarget()
    {
        return $this->sortedTarget;
    }

    /**
     * Set sortedTarget
     *
     * @param mixed $sortedTarget
     *
     * @return FiltrationResult
     */
    public function setSortedTarget($sortedTarget)
    {
        $this->sortedTarget = is_object($sortedTarget) ? clone $sortedTarget : $sortedTarget;

        return $this;
    }

    /**
     * Get paginatedTarget
     *
     * @return array
     */
    public function getPaginatedTarget()
    {
        return $this->paginatedTarget;
    }

    /**
     * Set paginatedTarget
     *
     * @param array $paginatedTarget
     *
     * @return FiltrationResult
     */
    public function setPaginatedTarget($paginatedTarget)
    {
        $this->paginatedTarget = $paginatedTarget;

        return $this;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param      $name
     * @param null $default
     *
     * @return null
     */
    public function getOption($name, $default = null)
    {
        return isset($this->options[$name]) ? $this->options[$name] : $default;
    }

    /**
     * @param $name
     * @param $value
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    /**
     * Set filteredTarget
     *
     * @param mixed $filteredTarget
     *
     * @return FiltrationResult
     */
    public function setFilteredTarget($filteredTarget)
    {
        $this->filteredTarget = is_object($filteredTarget) ? clone $filteredTarget : $filteredTarget;

        return $this;
    }
}
