<?php


namespace ITE\FiltrationBundle\EventListener;


use ITE\FiltrationBundle\Event\CallbackFilterEvent;
use ITE\FiltrationBundle\Event\FiltrationEvent;

/**
 * Class CallbackFiltrationListener
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class CallbackFiltrationListener extends AbstractFiltrationListener
{
    /**
     * @param FiltrationEvent $event
     */
    public function filter(FiltrationEvent $event)
    {
        $form = $event->getForm();
        $target = $event->getTarget();

        if (!$this->supportsParentType($form, 'callback_filter')) {
            return;
        }

        if (null === ($data = $form->getData())) {
            return;
        }

        $callbackEvent = new CallbackFilterEvent($form->get('callback_filter'), $target);
        call_user_func($form->getConfig()->getOption('callback'), $callbackEvent);

        if ($callbackEvent->isTargetModified()) {
            $target = $event->getTarget();
            $event->setTarget($target);
        } elseif ($callbackEvent->getCriteria()) {
            $event->setCriteria($callbackEvent->getCriteria());
        }
    }

}