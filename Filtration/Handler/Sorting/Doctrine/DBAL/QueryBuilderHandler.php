<?php

namespace ITE\FiltrationBundle\Filtration\Handler\Sorting\Doctrine\DBAL;

use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Query\QueryBuilder;
use ITE\FiltrationBundle\Filtration\Handler\HandlerInterface;
use ITE\FiltrationBundle\Doctrine\Common\Collections\Criteria as FiltrationCriteria;

/**
 * Class QueryBuilderHandler
 *
 * @author c1tru55 <mr.c1tru55@gmail.com>
 */
class QueryBuilderHandler implements HandlerInterface
{
    /**
     * @inheritDoc
     */
    public function handle($target, Criteria $criteria)
    {
        /** @var QueryBuilder $target */
        if (!$criteria instanceof FiltrationCriteria) {
            return $target;
        }

        $orderings = $criteria->getOrderings();
        if (!empty($orderings)) {
            $target->resetQueryPart('orderBy');
            foreach ($orderings as $field => $direction) {
                $target->addOrderBy($field, $direction);
            }
        }

        return $target;
    }

    /**
     * @inheritDoc
     */
    public function supports($target)
    {
        return $target instanceof QueryBuilder;
    }

}