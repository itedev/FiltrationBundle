<?php


namespace ITE\FiltrationBundle\EventListener\Pagination\Doctrine\DBAL;

use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManager;
use ITE\DoctrineExtraBundle\ORM\Query\ResultSetMappingBuilder;
use ITE\FiltrationBundle\Event\PaginationEvent;

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
        $target = $event->getTarget();

        $limit = $event->getOptions()->get('limit') ?: 10;
        $page = $event->getOptions()->get('page') ?: 1;
        $offset = abs($page - 1) * $limit;

        // get the query
        $sql = $target->getSQL();

        // count results
        $qb = clone $target;

        $rsm = new ResultSetMappingBuilder($this->em);
        $rsm->addScalarResult('cnt');

        $qb
            ->setResultSetMappingBuilder($rsm)
            ->resetQueryParts()
            ->select('COUNT(*) AS cnt')
            ->from('(' . $sql . ')', 'frm')
        ;

        $event->setCount($qb
            ->getQuery()
            ->getSingleScalarResult()
        );

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
                ->getResult()
            );
        }

        $event->stopPropagation();
    }
}
