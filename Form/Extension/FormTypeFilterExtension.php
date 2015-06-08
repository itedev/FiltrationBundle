<?php

namespace ITE\FiltrationBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class FormTypeFilterExtension
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class FormTypeFilterExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (isset($options['filter_form']) && true === $options['filter_form']) {
            $builder
                ->setRequired(false)
                ->setMethod('GET')
            ;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setOptional([
            'filter_form',
            'filter_field',
            'filter_aggregate',
        ]);
        $resolver->setAllowedTypes([
            'filter_form' => ['bool'],
            'filter_field' => ['string'],
            'filter_aggregate' => ['bool'],
        ]);
        $resolver->setDefaults([
           'csrf_protection' => function(Options $options, $csrfProtection) {
               if (isset($options['filter_form']) && true === $options['filter_form']) {
                   return false;
               }

               return $csrfProtection;
           },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'form';
    }

}