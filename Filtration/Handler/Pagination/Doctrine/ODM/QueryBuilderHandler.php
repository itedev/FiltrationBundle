<?php

namespace ITE\FiltrationBundle\Filtration\Handler\Pagination\Doctrine\ODM;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ODM\MongoDB\Query\Builder;
use ITE\FiltrationBundle\Filtration\Handler\HandlerInterface;

/**
 * Class QueryBuilderHandler
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class QueryBuilderHandler implements HandlerInterface
{
    /**
     * @inheritDoc
     */
    public function handle($target, Criteria $criteria)
    {
        /** @var Builder $target */
        $target
            ->limit($criteria->getMaxResults())
            ->skip($criteria->getFirstResult())
            ;

        return $target;
    }

    /**
     * @inheritDoc
     */
    public function supports($target)
    {
        return $target instanceof Builder;
    }
}