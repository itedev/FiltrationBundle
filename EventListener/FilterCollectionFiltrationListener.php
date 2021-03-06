<?php


namespace ITE\FiltrationBundle\EventListener;

use ITE\FiltrationBundle\Doctrine\Common\Collections\Criteria;
use ITE\FiltrationBundle\Event\FiltrationEvent;
use ITE\FiltrationBundle\Event\FiltrationEvents;
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
    private $dispatcher;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->dispatcher = $eventDispatcher;
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

        if (null === ($data = $form->getData())) {
            return;
        }

        /** @var Criteria $criteria */
        $criteria = null;

        /** @var FormInterface $child */
        foreach ($form as $child) {
            $childEvent = new FiltrationEvent($child, $target, $event->getOptions()->toArray(), $event->getFieldName());
            $this->dispatcher->dispatch(FiltrationEvents::FILTER, $childEvent);

            if ($childEvent->isTargetModified()) {
                $target = $childEvent->getTarget();
                $event->setTarget($target);
            } elseif ($childEvent->getCriteria()) {
                $criteria = $criteria ? $criteria->andWhere(
                    $childEvent->getCriteria()->getWhereExpression()
                ) : Criteria::create($childEvent->getCriteria()->getWhereExpression());
            }
        }

        if ($criteria) {
            $event->setCriteria($criteria);
        }
    }

}