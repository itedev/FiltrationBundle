<?php


namespace ITE\FiltrationBundle\EventListener\Pagination\Doctrine\ODM;

use Doctrine\ODM\MongoDB\Query\Builder;
use ITE\FiltrationBundle\Event\PaginationEvent;
use ITE\FiltrationBundle\Event\ResultEvent;

/**
 * Class QueryBuilderPaginationListener
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class QueryBuilderPaginationListener
{
    public function paginate(PaginationEvent $event)
    {
        if (!($event->getTarget() instanceof Builder)) {
            return;
        }

        if (!$event->getOption('paginate', false)) {
            return;
        }

        $limit = $event->getOptions()->get('limit') ?: 10;
        $page = $event->getOptions()->get('page') ?: 1;
        $offset = abs($page - 1) * $limit;

        /** @var Builder $clonedTarget */
        $clonedTarget = clone $event->getTarget();
        $count = $clonedTarget->count()->getQuery()->execute();

        $event->setTarget($event->getTarget()->limit($limit)->skip($offset)->getQuery()->toArray());
        $event->setCount($count);
        $event->stopPropagation();
    }

    public function result(ResultEvent $event)
    {
        if (!($event->getResult()->getOriginalTarget() instanceof Builder)) {
            return;
        }

        if ($event->getResult()->getFilterForm()->getConfig()->getOption('paginate')) {
            return;
        }

        if ($event->getResult()->getFilterForm()->getConfig()->getOption('force_result')) {
            return;
        }

        $event->setResult($event->getResult()->getSortedTarget());
    }
}
