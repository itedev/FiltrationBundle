<?php


namespace ITE\FiltrationBundle\Pager\Event\Subscriber\Sortable;


use Knp\Component\Pager\Event\ItemsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class DisableSortingSubscriber
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class DisableSortingSubscriber implements EventSubscriberInterface
{
    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            'knp_pager.items' => ['items', 128]
        ];
    }

    /**
     * @param ItemsEvent $event
     */
    public function items(ItemsEvent $event)
    {
        //@todo any other way to prevent sorting?
        $event->options['sortFieldParameterName'] = uniqid();
        $event->options['sortDirectionParameterName'] = uniqid();
    }
}