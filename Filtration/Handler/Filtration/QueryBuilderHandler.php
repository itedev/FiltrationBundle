<?php


namespace ITE\FiltrationBundle\Filtration\Handler\Filtration;

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
     * @var string
     */
    protected $expressionVisitorClass;

    /**
     * @param string $expressionVisitorClass
     */
    public function __construct($expressionVisitorClass)
    {
        $this->expressionVisitorClass = $expressionVisitorClass;
    }


    /**
     * {@inheritdoc}
     */
    public function handle($target, Criteria $criteria)
    {
        if (!$criteria instanceof FiltrationCriteria) {
            return $target->addCriteria($criteria);
        }

        $where = $criteria->getWhereExpression();
        $having = $criteria->getHavingExpression();

        if ($where) {
            /** @var QueryBuilderExpressionVisitor $visitor */
            $visitor = new $this->expressionVisitorClass();
            $whereCondition = $where->visit($visitor);
            $target->andWhere($whereCondition);

            foreach ($visitor->getParameters() as $parameter) {
                $target->setParameter($parameter->getName(), $parameter->getValue(), $parameter->getType());
            }
        }

        if ($having) {
            $visitor = new $this->expressionVisitorClass();
            $havingCondition = $having->visit($visitor);
            $target->andHaving($havingCondition);

            foreach ($visitor->getParameters() as $parameter) {
                $target->setParameter($parameter->getName(), $parameter->getValue(), $parameter->getType());
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