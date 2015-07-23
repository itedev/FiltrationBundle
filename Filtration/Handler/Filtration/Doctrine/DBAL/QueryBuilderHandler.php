<?php


namespace ITE\FiltrationBundle\Filtration\Handler\Filtration\Doctrine\DBAL;

use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Query\QueryBuilder;
use ITE\FiltrationBundle\Doctrine\DBAL\QueryBuilder\QueryBuilderExpressionVisitor;
use ITE\FiltrationBundle\Filtration\Handler\HandlerInterface;
use ITE\FiltrationBundle\Doctrine\Common\Collections\Criteria as FiltrationCriteria;
use ITE\FiltrationBundle\Filtration\Handler\Filtration\Doctrine\ORM\QueryBuilderHandler as ORMHandler;

/**
 * Class QueryBuilderHandler
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class QueryBuilderHandler extends ORMHandler
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

        return parent::handle($target, $criteria);
    }

    /**
     * @inheritDoc
     */
    public function supports($target)
    {
        return $target instanceof QueryBuilder;
    }

}