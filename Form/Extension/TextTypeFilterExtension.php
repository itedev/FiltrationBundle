<?php

namespace ITE\FiltrationBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class TextTypeFilterExtension
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class TextTypeFilterExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'filter_type' => function (Options $options) {
                return (isset($options['filter_aggregate']) && true === $options['filter_aggregate']) ? 'equals' : 'contains';
            },
            'matching_type' => 'case_sensitive',
        ]);
        $resolver->setAllowedValues([
            'filter_type' => [
                'contains',
                'equals',
            ],
            'matching_type' => [
                'case_sensitive',
                'case_insensitive',
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'text';
    }
}