<?php


namespace ITE\FiltrationBundle\Form\Extension;


use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FormTypeFilterExtension extends AbstractTypeExtension
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired([
            'filter_form',
        ]);
        $resolver->setOptional([
            'filter_field',
            'filter_aggregate'
        ]);
        $resolver->setAllowedTypes([
            'filter_form' => 'bool',
            'filter_aggregate' => 'bool',
        ]);
        $resolver->setDefaults([
            'filter_form' => false,
            'filter_field' => null,
            'filter_aggregate' => false
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['filter_form'] === true) {
            $builder->setRequired(false);
        }
    }


    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return 'form';
    }

}