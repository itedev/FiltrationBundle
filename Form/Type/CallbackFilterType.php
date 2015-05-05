<?php


namespace ITE\FiltrationBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CallbackFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('callback_filter', $options['type'], $options['options']);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(['callback', 'type']);
        $resolver->setOptional(['options']);
        $resolver->setDefaults(['options' => []]);
        $resolver->setAllowedTypes(['callback' => 'callable']);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'callback_filter';
    }

}