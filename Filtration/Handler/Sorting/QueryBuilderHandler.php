<?php


namespace ITE\FiltrationBundle\Filtration\Handler\Sorting;

use Doctrine\Common\Collections\Criteria;
use ITE\FiltrationBundle\Doctrine\Common\Collections\Criteria as FiltrationCriteria;
use Doctrine\ORM\QueryBuilder;
use ITE\FiltrationBundle\Doctrine\ORM\QueryBuilder\QueryBuilderExpressionVisitor;
use ITE\FiltrationBundle\Filtration\Handler\HandlerInterface;

/**
 * Class QueryBuilderHandler
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class QueryBuilderHandler implements HandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle($target, Criteria $criteria)
    {
        if (!$criteria instanceof FiltrationCriteria) {
            return $target->addCriteria($criteria);
        }

        $orderings = $criteria->getOrderings();

        if (!empty($orderings)) {
            $target->resetDQLPart('orderBy');
            foreach ($orderings as $field => $direction) {
                $target->addOrderBy($field, $direction);
            }
        }

        return $target;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($target)
    {
        return $target instanceof QueryBuilder;
    }

}