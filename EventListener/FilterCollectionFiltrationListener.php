<?php


namespace ITE\FiltrationBundle\EventListener;

use ITE\FiltrationBundle\Event\FiltrationEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Class FilterCollectionFiltrationListener
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class FilterCollectionFiltrationListener extends AbstractFiltrationListener
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param FiltrationEvent $event
     * @return mixed
     */
    public function filter(FiltrationEvent $event)
    {
        $form = $event->getForm();
        $target = $event->getTarget();

        if (!$this->supportsParentType($form, 'filter_collection')) {
            return;
        }

        if (!$data = $form->getData()) {
            return;
        }

        /** @var FormInterface $child */
        foreach ($form as $child) {
            $childEvent = new FiltrationEvent($child, $target, $event->getFieldName());
            $this->eventDispatcher->dispatch(FiltrationEvent::EVENT_NAME, $childEvent);
            $event->setTarget($childEvent->getTarget());
        }
    }

}