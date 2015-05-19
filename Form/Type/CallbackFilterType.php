<?php

namespace ITE\FiltrationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CallbackFilterType
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class CallbackFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('callback_filter', $options['type'], $options['options']);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(['callback', 'type']);
        $resolver->setOptional(['options']);
        $resolver->setDefaults(['options' => []]);
        $resolver->setAllowedTypes(['callback' => 'callable']);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'callback_filter';
    }

}