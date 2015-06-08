<?php

namespace ITE\FiltrationBundle\Filtration\Filter;

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
    protected $modifiedFields = [];

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
    public function markFieldModified($fieldName)
    {
        $this->modifiedFields[] = $fieldName;
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
            'translation_domain' => 'ITEFiltrationBundle'
        ]);
    }
} 