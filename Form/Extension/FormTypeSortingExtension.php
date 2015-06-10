<?php


namespace ITE\FiltrationBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class FormTypeSortingExtension
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class FormTypeSortingExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (isset($options['sort'])) {
            $builder->add('sort', 'hidden');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (isset($options['sort']) && $options['sort'] === true) {
            $view->vars['sort'] = $options['sort'];
            $sortLabelAsc = isset ($options['sort_label_asc']) ? $options['sort_label_asc'] : isset ($options['sort_label']) ? $options['sort_label'].'.asc' : 'sort.asc';
            $sortLabelDesc = isset ($options['sort_label_desc']) ? $options['sort_label_desc'] : isset ($options['sort_label']) ? $options['sort_label'].'.desc' : 'sort.desc';

            $view->vars['sort_label_asc'] = $sortLabelAsc;
            $view->vars['sort_label_desc'] = $sortLabelDesc;

            if (isset($options['sort_reset_link']) && $options['sort_reset_link'] === true) {
                $view->vars['sort_reset_link'] = true;
                $view->vars['sort_reset_link_label'] = isset ($options['sort_reset_link_label']) ? $options['sort_reset_link_label'] : 'sort.reset';
                $view->vars['sort_reset_link_attr'] = isset ($options['sort_reset_link_attr']) ? $options['sort_reset_link_attr'] : [];
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setOptional([
            'sort',
            'sort_multiple',
            'sort_order',
            'sort_field',
            'sort_label',
            'sort_link_attr',
            'sort_label_asc',
            'sort_label_desc',
            'sort_reset_link',
            'sort_reset_link_label',
            'sort_reset_link_attr',
        ]);
        $resolver->setAllowedTypes([
            'sort'                  => ['bool'],
            'sort_multiple'         => ['bool'],
            'sort_order'            => ['int'],
            'sort_field'            => ['string'],
            'sort_label'            => ['string'],
            'sort_label_asc'        => ['string'],
            'sort_label_desc'       => ['string'],
            'sort_reset_link'       => ['bool'],
            'sort_reset_link_label' => ['string'],
            'sort_reset_link_attr'  => ['array'],
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