<?php


namespace ITE\FiltrationBundle\EventListener\Sorting;

use Doctrine\Common\Collections\Criteria;
use ITE\FiltrationBundle\Event\FiltrationEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class SortingEventListener
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 * @todo make query params configurable
 */
class SortingEventListener
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }


    /**
     * @param FiltrationEvent $event
     */
    public function sort(FiltrationEvent $event)
    {
        $form = $event->getForm();
        $criteria = null;

        if (!($sortField = $this->getSortField($form))) {
            return;
        }

        if (!($this->requestStack->getMasterRequest()->query->get('sort'.$sortField))) {
            return;
        }

        if (!($direction = $this->requestStack->getMasterRequest()->query->get('direction'.$sortField))) {
            return;
        }

        if ($orderings = $event->getOptions()->get('orderings')) {
            $orderings[$sortField]= $direction;
        } else {
            $orderings = [
                $sortField => $direction
            ];
        }
        $event->getOptions()->set('orderings', $orderings);

        $criteria = Criteria::create();
        $criteria->orderBy($orderings);
        $event->setCriteria($criteria);
    }

    /**
     * Validate that current ordering is allowed.
     *
     * @param $form
     * @return bool
     */
    protected function getSortField(FormInterface $form)
    {
        if (!($sorting = $form->getConfig()->getOption('filter_sorting'))) {
            return false;
        } else {
            return $form->getConfig()->getOption('filter_field') ?: $form->getName();
        }
    }
}