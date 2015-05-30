<?php

namespace ITE\FiltrationBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
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
            'filter_type' => 'contains',
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