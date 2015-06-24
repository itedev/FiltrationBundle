<?php


namespace ITE\FiltrationBundle\Filtration\Result;

use ITE\FiltrationBundle\Filtration\Filter\AbstractFilter;
use Symfony\Component\Form\FormInterface;

/**
 * Class FiltrationResult
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class FiltrationResult extends AbstractFiltrationResult
{
    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var AbstractFilter
     */
    private $filter;

    /**
     * @var int
     */
    private $count;

    /**
     * @param FormInterface   $form
     * @param AbstractFilter $filter
     * @param array           $options
     */
    public function __construct(FormInterface $form, AbstractFilter $filter, $options = [])
    {
        $this->form = $form;
        $this->filter = $filter;
        $this->options = $options;
    }

    /**
     * @param $fieldName
     * @return bool
     */
    public function isFieldModified($fieldName)
    {
        return $this->filter->isFieldModified($fieldName);
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return isset($this->options['limit']) ? $this->options['limit'] : 10;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return isset($this->options['page']) ? $this->options['page'] : 10;
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
     * @return FiltrationResult
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

}