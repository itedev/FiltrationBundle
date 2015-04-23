<?php


namespace ITE\FiltrationBundle\Form\Type;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class TextFilterType
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class TextFilterType extends TextType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults([
              'filter_type' => 'contains'
          ]);
        $resolver->setAllowedValues([
              'filter_type' => [
                  'contains', 'equals'
              ]
          ]);
    }

    public function getName()
    {
        return 'text_filter';
    }

} 