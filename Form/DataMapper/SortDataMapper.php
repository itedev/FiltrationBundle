<?php


namespace ITE\FiltrationBundle\Form\DataMapper;

use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception;
use Symfony\Component\Form\FormInterface;

/**
 * Class SortDataMapper
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class SortDataMapper implements DataMapperInterface
{
    /**
     * Maps properties of some data to a list of forms.
     *
     * @param mixed           $data  Structured data.
     * @param FormInterface[] $forms A list of {@link FormInterface} instances.
     *
     * @throws Exception\UnexpectedTypeException if the type of the data parameter is not supported.
     */
    public function mapDataToForms($data, $forms)
    {
        // TODO: Implement mapDataToForms() method.
    }

    /**
     * Maps the data of a list of forms into the properties of some data.
     *
     * @param FormInterface[] $forms A list of {@link FormInterface} instances.
     * @param mixed           $data  Structured data.
     *
     * @throws Exception\UnexpectedTypeException if the type of the data parameter is not supported.
     */
    public function mapFormsToData($forms, &$data)
    {
        // TODO: Implement mapFormsToData() method.
    }

}