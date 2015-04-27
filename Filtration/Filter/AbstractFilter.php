<?php


namespace ITE\FiltrationBundle\Filtration\Filter;


use ITE\FiltrationBundle\Filtration\FilterInterface;

/**
 * Class AbstractFilter
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
abstract class AbstractFilter implements FilterInterface
{
    /**
     * @var array
     */
    protected $modifiedFields = [];
    /**
     * @return string
     */
    public function getTemplateName()
    {
        return 'ITEFiltration:Filter:raw_form.html.twig';
    }

    /**
     * @param $fieldName
     */
    public function markFieldModified($fieldName)
    {
        $this->modifiedFields []= $fieldName;
    }

    /**
     * @return array
     */
    public function getModifiedFields()
    {
        return $this->modifiedFields;
    }

    /**
     * @param $fieldName
     * @return bool
     */
    public function isFieldModified($fieldName)
    {
        return in_array($fieldName, $this->modifiedFields);
    }
} 