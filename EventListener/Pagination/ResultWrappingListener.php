<?php


namespace ITE\FiltrationBundle\EventListener\Pagination;

use Doctrine\ORM\Tools\Pagination\Paginator;
use ITE\FiltrationBundle\Event\PaginationEvent;
use ITE\FiltrationBundle\Filtration\Result\FiltrationResult;

/**
 * Class ResultWrappingListener
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class ResultWrappingListener
{

    public function paginate(PaginationEvent $event)
    {
        if (!$event->getOptions()->get('wrap_result')) {
            return;
        }

        if (!($event->getTarget() instanceof Paginator)) {
            return;
        }

        $result = new FiltrationResult($event->getForm(), $event->getFilter(), $event->getOptions());
        $result->setItems(iterator_to_array($event->getTarget()));
        $result->setCount(count($event->getTarget()));

        $event->setTarget($result);
    }

}