<?php


namespace ITE\FiltrationBundle\EventListener\Sorting;

use Doctrine\Common\Collections\Criteria;
use ITE\FiltrationBundle\Event\FiltrationEvent;
use ITE\FiltrationBundle\Event\SortingEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class SortingEventListener
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class SortingEventListener
{
    /**
     * @param SortingEvent $event
     */
    public function sort(SortingEvent $event)
    {
        $form = $event->getForm();
        $criteria = null;

        if (!($sortField = $this->getSortField($form))) {
            return;
        }

        $direction = $form->getConfig()->getAttribute('ite_sort_direction');

        if (!$direction) {
            return;
        }

        $event->addOrderBy([
            $sortField => $direction
        ], $form->getConfig()->getOption('sort_order', 0));
    }

    /**
     * Validate that current ordering is allowed.
     *
     * @param $form
     * @return bool
     */
    protected function getSortField(FormInterface $form)
    {
        if (!$form->getConfig()->getOption('sort')) {
            return false;
        } else {
            return $form->getConfig()->getOption('sort_field') ?: $form->getName();
        }
    }
}