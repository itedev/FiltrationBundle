<?php

namespace ITE\FiltrationBundle\Filtration\Handler\Sorting\Doctrine\ODM;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ODM\MongoDB\Query\Builder;
use ITE\Common\Util\ReflectionUtils;
use ITE\FiltrationBundle\Filtration\Handler\HandlerInterface;

/**
 * Class QueryBuilderHandler
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class QueryBuilderHandler implements HandlerInterface
{
    public function handle($target, Criteria $criteria)
    {
        /** @var Builder $target */
        $orderings = $criteria->getOrderings();

        if (!empty($orderings)) {
            $query = ReflectionUtils::getValue($target, 'query');
            $query['sort'] = [];
            foreach ($orderings as $field => $direction) {
                $target->sort($field, $direction);
            }
        }

        return $target;
    }

    public function supports($target)
    {
        return $target instanceof Builder;
    }
}