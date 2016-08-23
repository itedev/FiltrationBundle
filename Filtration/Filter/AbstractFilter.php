<?php

namespace ITE\FiltrationBundle\Filtration\Filter;

use Doctrine\ORM\Query;
use ITE\FiltrationBundle\Filtration\FilterInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
    protected $filteredFields = [];
    protected $sortedFields = [];

    /**
     * @return string
     */
    public function getTemplateName()
    {
        return '@ITEFiltrationBundle/Resources/views/Filter/raw_form.html.twig';
    }

    /**
     * @param $fieldName
     */
    public function markFieldFiltered($fieldName)
    {
        $this->filteredFields[] = $fieldName;
    }

    /**
     * @return array
     */
    public function getFilteredFields()
    {
        return $this->filteredFields;
    }

    /**
     * @param $fieldName
     * @return bool
     */
    public function isFieldFiltered($fieldName)
    {
        return in_array($fieldName, $this->filteredFields);
    }

    /**
     * @param $fieldName
     * @param $direction
     */
    public function markFieldSorted($fieldName, $direction)
    {
        $this->sortedFields[$fieldName] = $direction;
    }

    /**
     * @return array
     */
    public function getSortedFields()
    {
        return $this->sortedFields;
    }

    /**
     * @param $fieldName
     * @return bool
     */
    public function isFieldSorted($fieldName)
    {
        return array_key_exists($fieldName, $this->sortedFields);
    }

    /**
     * @param $fieldName
     * @return string
     */
    public function getFieldSortingDirection($fieldName)
    {
        if (!$this->isFieldSorted($fieldName)) {
            return false;
        }

        return $this->sortedFields[$fieldName];
    }

    /**
     * @inheritdoc
     */
    public function getOptions(array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->setOptions($resolver);

        return $resolver->resolve($options);
    }

    /**
     * @param OptionsResolver $resolver
     */
    protected function setOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'translation_domain' => 'ITEFiltrationBundle',
            'hydrator' => Query::HYDRATE_OBJECT,
            'wrap_result' => false,
            'paginate' => false,
            'limit' => 10,
            'page' => 1,
            'pagination' => [
                'page_parameter_name' => 'page',
                'wrap_queries' => false,
                'distinct' => false,
                'fetch_join_collection' => false,
            ],
        ]);
    }
}
