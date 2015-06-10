<?php


namespace ITE\FiltrationBundle\Form\Data;

/**
 * Class FilterData
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class FilterData {
    /**
     * @var mixed
     */
    private $filter;

    /**
     * @var string
     */
    private $sort;

    /**
     * @var string
     */
    private $originalName;

    /**
     * @param        $originalName
     * @param mixed  $filter
     * @param string $sort
     */
    function __construct($originalName, $filter = null, $sort = null)
    {
        $this->filter = $filter;
        $this->sort   = $sort;
        $this->originalName = $originalName;
    }

    /**
     * Get filter
     *
     * @return mixed
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * Set filter
     *
     * @param mixed $filter
     * @return FilterData
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * Get sort
     *
     * @return string
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Set sort
     *
     * @param string $sort
     * @return FilterData
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get originalName
     *
     * @return string
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }
}