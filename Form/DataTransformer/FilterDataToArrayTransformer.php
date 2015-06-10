<?php


namespace ITE\FiltrationBundle\Form\DataTransformer;

use ITE\FiltrationBundle\Form\Data\FilterData;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Class FilterDataToArrayTransformer
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class FilterDataToArrayTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        if (null === $value) {
            return;
        }

        if (!($value instanceof FilterData)) {
            throw new TransformationFailedException('Expected a \ITE\FiltrationBundle\Form\Data\FilterData.');
        }

        return [
            $value->getOriginalName() => $value->getFilter(),
            'sort' => $value->getSort(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if (null === $value) {
            return;
        }

        if (!is_array($value)) {
            throw new TransformationFailedException('Expected an array.');
        }

        $originalName = null;
        $filter = null;
        $sort = null;

        foreach ($value as $key => $val) {
            if ($key == 'sort') {
                $sort = $val;
            } else {
                $originalName = $key;
                $filter = $val;
            }
        }


        return new FilterData($originalName, $filter, $sort);
    }

}