<?php


namespace ITE\FiltrationBundle\Form\Extension;

use ITE\FiltrationBundle\Form\DataMapper\SortDataMapper;
use ITE\FiltrationBundle\Form\DataTransformer\FilterDataToArrayTransformer;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\DataMapper\PropertyPathMapper;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\ResolvedFormType;
use Symfony\Component\Form\ResolvedFormTypeInterface;
use Symfony\Component\OptionsResolver\Options;
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
        if (isset($options['sort']) && $options['sort'] === true) {
            $builder->addEventListener(
                FormEvents::PRE_SUBMIT,
                function (FormEvent $event) {
                    $data = $event->getData();

                    if (!is_array($data)) {
                        return;
                    }

                    if (!isset($data['sort'])) {
                        if (isset($data['filter'])) {
                            $event->setData($data['filter']);
                        }
                        return;
                    }

                    $sort = $data['sort'];

                    /** @var FormBuilder $config */
                    $config = $event->getForm()->getConfig();
                    $ref = new \ReflectionObject($config);
                    $prop = $ref->getProperty('locked');
                    $prop->setAccessible(true);
                    $prop->setValue($config, false);
                    $config->setAttribute('ite_sort_direction', $sort);
                    $prop->setValue($config, true);

                    $data = isset($data['filter']) ? $data['filter'] : null;

                    $event->setData($data);
                }
            );
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
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if (isset($options['sort']) && $options['sort'] === true) {
            $factory = $form->getConfig()->getFormFactory();
            $direction = $form->getConfig()->getAttribute('ite_sort_direction');
            $sortForm = $factory->createNamed('sort', 'hidden', $direction, array(
                'mapped' => false,
            ));

            $view->vars['sort_form'] = $sortForm->createView($view);
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