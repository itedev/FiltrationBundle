<?php

namespace ITE\FiltrationBundle\Filtration\Result;

use ITE\FiltrationBundle\Filtration\FilterInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Class FiltrationResult
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class FiltrationResult implements FiltrationResultInterface
{
    use FiltrationResultTrait;

    /**
     * FiltrationResult constructor.
     *
     * @param FilterInterface $filter
     * @param FormInterface   $filterForm
     * @param                 $originalTarget
     * @param array           $options
     */
    public function __construct(FilterInterface $filter, FormInterface $filterForm, $originalTarget, $options = [])
    {
        $this->filter = $filter;
        $this->filterForm = $filterForm;
        $this->originalTarget = is_object($originalTarget) ? clone $originalTarget : $originalTarget;
        $this->filteredTarget = $this->sortedTarget = $this->originalTarget;
        $this->options = $options;
    }
}
