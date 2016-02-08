<?php


namespace ITE\FiltrationBundle\EventListener\Pagination\Doctrine;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use ITE\FiltrationBundle\Event\PaginationEvent;

/**
 * Class ArrayCollectionPaginationListener
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class ArrayCollectionPaginationListener
{
    public function paginate(PaginationEvent $event)
    {
        if (!($event->getTarget() instanceof ArrayCollection)) {
            return;
        }

        $limit = $event->getOptions()->get('limit') ?: 10;
        $page = $event->getOptions()->get('page') ?: 1;
        $offset = abs($page - 1) * $limit;
        $event->setCount($event->getTarget()->count());

        $criteria = Criteria::create();
        $criteria->setMaxResults($limit);
        $criteria->setFirstResult($offset);

        $event->setTarget($event->getTarget()->matching($criteria));
    }
}
