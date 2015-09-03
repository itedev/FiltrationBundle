<?php


namespace ITE\FiltrationBundle\Filtration;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Interface FilterInterface
 */
interface FilterInterface
{
    /**
     * @param FormFactoryInterface $formFactory
     * @return FormInterface
     */
    public function getFilterForm(FormFactoryInterface $formFactory);

    /**
     * @param $fieldName
     */
    public function markFieldFiltered($fieldName);

    /**
     * @param $fieldName
     * @param $direction
     */
    public function markFieldSorted($fieldName, $direction);

    /**
     * @return string
     */
    public function getTemplateName();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param array $options
     * @return array
     */
    public function getOptions(array $options = []);
} 