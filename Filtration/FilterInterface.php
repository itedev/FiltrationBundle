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
     * @return string
     */
    public function getTemplateName();

    /**
     * @return string
     */
    public function getName();
} 