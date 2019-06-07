<?php

namespace ITE\FiltrationBundle\Filtration\Handler\Filtration\Doctrine\ODM;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ODM\MongoDB\Query\Builder;
use ITE\FiltrationBundle\Doctrine\ODM\MongoDB\Query\QueryExpressionVisitor;
use ITE\FiltrationBundle\Filtration\Handler\HandlerInterface;

/**
 * Class QueryBuilderHandler
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class QueryBuilderHandler implements HandlerInterface
{
    /**
     * {@inheritDoc}
     */
    public function handle($target, Criteria $criteria)
    {
        /** @var Builder $target */
        $where = $criteria->getWhereExpression();
        $having = $criteria->getHavingExpression();
        $visitor = new QueryExpressionVisitor($target);

        if ($where) {
            $whereCondition = $where->visit($visitor);
            $target->addAnd($whereCondition);
        }

        if ($having) {
            $havingCondition = $having->visit($visitor);
            $target->addAnd($whereCondition);
        }

        return $target;
    }

    /**
     * {@inheritDoc}
     */
    public function supports($target)
    {
        return $target instanceof Builder;
    }
}