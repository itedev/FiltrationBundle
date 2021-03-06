<?php


namespace ITE\FiltrationBundle\EventListener\Pagination\Doctrine\ORM;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\CountWalker;
use Doctrine\ORM\Tools\Pagination\Paginator;
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
        if (!($event->getTarget() instanceof QueryBuilder)) {
            return;
        }

        if (!$event->getOption('paginate', false)) {
            return;
        }

        $limit = $event->getOptions()->get('limit') ?: 10;
        $page = $event->getOptions()->get('page') ?: 1;
        $offset = abs($page - 1) * $limit;

        if (!class_exists('Doctrine\ORM\Tools\Pagination\Paginator')) {
            return;
        }

        $target = $event->getTarget()->getQuery();

        $paginationOptions = $event->getOptions()->get('pagination') ?: [];
        $useOutputWalkers = false;
        if (isset($paginationOptions['wrap_queries']) && true === $paginationOptions['wrap_queries']) {
            $useOutputWalkers = true;
        }

        $distinct = isset($paginationOptions['distinct']) && true === $paginationOptions['distinct'] ? true : false;
        $partial = isset($paginationOptions['partial']) && true === $paginationOptions['partial'] ? true : false;
        $target
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->setHydrationMode($event->getOptions()->get('hydrator'))
            ->setHint(CountWalker::HINT_DISTINCT, $distinct)
        ;

        if ($partial) {
            $target->setHint(Query::HINT_FORCE_PARTIAL_LOAD, 1);
        }

        $fetchJoinCollection = true;
        if (isset($paginationOptions['fetch_join_collection']) && true === $paginationOptions['fetch_join_collection']) {
            $fetchJoinCollection = true;
        }

        $paginator = new Paginator($target, $fetchJoinCollection);
        $paginator->setUseOutputWalkers($useOutputWalkers);

        $event->setTarget($paginator->getIterator()->getArrayCopy());
        $event->setCount($paginator->count());
        $event->stopPropagation();
    }

    public function result(ResultEvent $event)
    {
        if (!($event->getResult()->getOriginalTarget() instanceof QueryBuilder)) {
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
