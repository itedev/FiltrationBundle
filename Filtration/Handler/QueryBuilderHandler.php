<?php


namespace ITE\FiltrationBundle\Filtration\Handler;

use Doctrine\Common\Collections\Criteria;
use ITE\FiltrationBundle\Doctrine\Common\Collections\Criteria as FiltrationCriteria;
use Doctrine\ORM\QueryBuilder;
use ITE\FiltrationBundle\Doctrine\ORM\QueryBuilder\QueryBuilderExpressionVisitor;

/**
 * Class QueryBuilderHandler
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class QueryBuilderHandler implements HandlerInterface
{
    /**
     * Handle target with a given criteria.
     *
     * @param  QueryBuilder $target   The target to handle
     * @param  Criteria $criteria Criteria to handle with
     * @return mixed    Modified $target
     */
    public function handle($target, Criteria $criteria)
    {
        if (!$criteria instanceof FiltrationCriteria) {
            return $target->addCriteria($criteria);
        }

        $where = $criteria->getWhereExpression();
        $having = $criteria->getHavingExpression();

        if ($where) {
            $visitor = new QueryBuilderExpressionVisitor();
            $whereCondition = $where->visit($visitor);
            $target->andWhere($whereCondition);

            foreach ($visitor->getParameters() as $parameter) {
                $target->setParameter($parameter->getName(), $parameter->getValue(), $parameter->getType());
            }
        }

        if ($having) {
            $visitor = new QueryBuilderExpressionVisitor();
            $havingCondition = $having->visit($visitor);
            $target->andHaving($havingCondition);

            foreach ($visitor->getParameters() as $parameter) {
                $target->setParameter($parameter->getName(), $parameter->getValue(), $parameter->getType());
            }
        }

        $orderings = $criteria->getOrderings();

        if (!empty($orderings)) {
            foreach ($orderings as $field => $direction) {
                $target->addOrderBy($field, $direction);
            }
        }

        return $target;
    }

    /**
     * Checks that given target is able to be handled.
     *
     * @param $target
     * @return bool
     */
    public function supports($target)
    {
        return $target instanceof QueryBuilder;
    }

}