<?php


namespace ITE\FiltrationBundle\Filtration\Handler\Pagination\Doctrine\DBAL;

use Doctrine\Common\Collections\Criteria;
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
        // TODO: Implement handle() method.
    }

    /**
     * @inheritDoc
     */
    public function supports($target)
    {
        // TODO: Implement supports() method.
    }
}
