<?php


namespace ITE\FiltrationBundle\EventListener\Pagination\Doctrine\DBAL;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManager;
use ITE\DoctrineExtraBundle\ORM\Query\ResultSetMappingBuilder;
use ITE\FiltrationBundle\Event\PaginationEvent;
use ITE\FiltrationBundle\Event\ResultEvent;

/**
 * Class QueryBuilderPaginationListener
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class QueryBuilderPaginationListener
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * QueryBuilderPaginationListener constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function paginate(PaginationEvent $event)
    {
        if (!($event->getTarget() instanceof QueryBuilder)) {
            return;
        }

        if (!$event->getForm()->getConfig()->getOption('paginate') && !$event->getOption('paginate', false)) {
            return;
        }

        $target = $event->getTarget();

        $limit = $event->getOptions()->get('limit') ?: 10;
        $page = $event->getOptions()->get('page') ?: 1;
        $offset = abs($page - 1) * $limit;

        if (!$event->getCount()) {
            // count results
            $tmpQb = clone $target;

            /** @var QueryBuilder $qb */
            if ($qb = $event->getOptions()->get('pagination_query_builder')) {
                if ($qbWherePart = $tmpQb->getQueryPart('where')) {
                    $qb->where($qbWherePart);
                }

                if ($qbHavingPart = $tmpQb->getQueryPart('having')) {
                    $qb->having($qbHavingPart);
                }

                $qb->setParameters($tmpQb->getParameters());
            }
            else {
                $qb = $tmpQb;
            }

            $qb->resetQueryPart('orderBy');

            $sql = $qb->getSQL();


            $rsm = new ResultSetMappingBuilder($this->em);
            $rsm->addScalarResult('cnt');

            $qb
                ->setResultSetMappingBuilder($rsm)
                ->resetQueryParts()
                ->select('COUNT(*) AS cnt')
                ->from('(' . $sql . ')', 'frm');

            $event->setCount($qb
                ->getQuery()
                ->getSingleScalarResult()
            );
        }

        // if there is results
        $event->setTarget([]);
        if ($event->getCount()) {
            $qb = clone $target;
            $qb
                ->setFirstResult($offset)
                ->setMaxResults($limit)
            ;

            $event->setTarget($qb
                ->getQuery()
                ->getResult($event->getOptions()->get('hydrator'))
            );
        }

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
