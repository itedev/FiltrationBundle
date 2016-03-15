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
        if (!is_array($event->getTarget()) && !($event->getTarget() instanceof ArrayCollection)) {
            return;
        }

        if (is_array($event->getTarget())) {
            $target = new ArrayCollection($event->getTarget());
        } else {
            $target = $event->getTarget();
        }

        $limit = $event->getOptions()->get('limit') ?: 10;
        $page = $event->getOptions()->get('page') ?: 1;
        $offset = abs($page - 1) * $limit;
        $event->setCount($target->count());

        $criteria = Criteria::create();
        $criteria->setMaxResults($limit);
        $criteria->setFirstResult($offset);

        $event->setTarget($target->matching($criteria));
        $event->stopPropagation();
    }
}
