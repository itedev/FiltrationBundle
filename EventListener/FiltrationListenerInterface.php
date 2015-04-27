<?php


namespace ITE\FiltrationBundle\EventListener;

use ITE\FiltrationBundle\Event\FiltrationEvent;

/**
 * Interface FiltrationListenerInterface
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
interface FiltrationListenerInterface
{
    /**
     * @param FiltrationEvent $event
     */
    public function filter(FiltrationEvent $event);
}