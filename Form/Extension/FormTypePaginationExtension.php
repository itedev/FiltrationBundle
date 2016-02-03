<?php


namespace ITE\FiltrationBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class FormTypePaginationExtention
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class FormTypePaginationExtension extends AbstractTypeExtension
{
    /**
     * @inheritDoc
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'paginate' => false,
            'paginate_page' => 1,
            'paginate_limit' => 10,
        ]);
        $resolver->setAllowedTypes([
            'paginate' => ['bool'],
            'paginate_page' => ['integer', 'callable'],
            'paginate_limit' => ['integer', 'callable'],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getExtendedType()
    {
        return 'form';
    }
}
