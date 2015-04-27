<?php


namespace ITE\FiltrationBundle\Doctrine\Common\Collections;

use Doctrine\Common\Collections\ArrayCollection as BaseCollection;
use Doctrine\Common\Collections\Criteria;
use ITE\FiltrationBundle\Doctrine\Common\Collections\Expr\ClosureExpressionVisitor;

/**
 * Class ArrayCollection
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class ArrayCollection extends BaseCollection
{
    /**
     * @inheritdoc
     */
    public function matching(Criteria $criteria)
    {
        $expr     = $criteria->getWhereExpression();
        $filtered = $this->toArray();

        if ($expr) {
            $visitor  = new ClosureExpressionVisitor();
            $filter   = $visitor->dispatch($expr);
            $filtered = array_filter($filtered, $filter);
        }

        if ($orderings = $criteria->getOrderings()) {
            foreach (array_reverse($orderings) as $field => $ordering) {
                $next = ClosureExpressionVisitor::sortByField($field, $ordering == Criteria::DESC ? -1 : 1);
            }

            uasort($filtered, $next);
        }

        $offset = $criteria->getFirstResult();
        $length = $criteria->getMaxResults();

        if ($offset || $length) {
            $filtered = array_slice($filtered, (int)$offset, $length);
        }

        return new static($filtered);
    }

}