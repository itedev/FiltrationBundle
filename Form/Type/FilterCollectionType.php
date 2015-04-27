<?php


namespace ITE\FiltrationBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class FilterCollectionType
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class FilterCollectionType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $filters = $options['filters'];
        foreach ($filters as $key => $filter) {
            if (!isset($filter[1])) {
                $filter[1] = [];
            }
            if (count($filter) !== 2) {
                throw new \InvalidArgumentException('Filter should be an array of 2 values: filter and options.');
            }

            $builder->add($key, $filter[0], $filter[1]);
        }

    }

    /**
     * @inheritdoc
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(['filters']);
        $resolver->setAllowedTypes(['filters' => 'array']);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'filter_collection';
    }

}