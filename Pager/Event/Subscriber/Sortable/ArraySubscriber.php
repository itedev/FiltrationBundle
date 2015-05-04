<?php


namespace ITE\FiltrationBundle\Pager\Event\Subscriber\Sortable;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Knp\Component\Pager\Event\ItemsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ArraySubscriber
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class ArraySubscriber implements EventSubscriberInterface
{
    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            'knp_pager.items' => ['items', 1]
        ];
    }

    /**
     * @param ItemsEvent $event
     */
    public function items(ItemsEvent $event)
    {
        if (!is_array($event->target) && !($event->target instanceof ArrayCollection)) {
            return;
        }

        $target = $event->target instanceof ArrayCollection ? $event->target : new ArrayCollection($event->target);

        if (!isset($_GET[$event->options['sortFieldParameterName']])) {
            return;
        }

        $dir      = isset($_GET[$event->options['sortDirectionParameterName']])
        && strtolower($_GET[$event->options['sortDirectionParameterName']]) === 'asc' ? 'asc' : 'desc';
        $field    = $_GET[$event->options['sortFieldParameterName']];
        $criteria = Criteria::create();
        $criteria->orderBy([$field => $dir]);
        $event->target = $target->matching($criteria);
    }
}