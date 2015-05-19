<?php

namespace ITE\FiltrationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class RangeFilterType
 *
 * @author c1tru55 <mr.c1tru55@gmail.com>
 */
class RangeFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('from', $options['type'], $options['options_from'])
            ->add('to', $options['type'], $options['options_to'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'options_from' => [],
            'options_to' => []
        ]);
        $resolver->setRequired(['type']);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'range_filter';
    }

} 