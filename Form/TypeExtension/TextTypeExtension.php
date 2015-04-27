<?php


namespace ITE\FiltrationBundle\Form\TypeExtension;


use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class TextTypeExtension
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class TextTypeExtension extends AbstractTypeExtension
{
    /**
     * @inheritdoc
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'filter_type' => 'contains'
        ]);
        $resolver->setAllowedValues([
            'filter_type' => [
                'contains', 'equals'
            ]
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getExtendedType()
    {
        return 'text';
    }
}