<?php


namespace ITE\FiltrationBundle\EventListener\Sorting;

use Doctrine\Common\Collections\Criteria;
use ITE\FiltrationBundle\Event\FiltrationEvent;
use ITE\FiltrationBundle\Event\SortingEvent;
use ITE\FiltrationBundle\Util\UrlGeneratorInterface;
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
        $parent = $form->getParent();
        $criteria = null;

        if (!$parent->has(UrlGeneratorInterface::SORT_FIELD_PREFIX.$form->getName())) {
            return;
        }

        $sortForm = $parent->get(UrlGeneratorInterface::SORT_FIELD_PREFIX.$form->getName());
        $direction = $sortForm->getData();

        if (!$direction) {
            return;
        }

        $config = $form->getConfig();
        $sortField = $config->getOption('sort_field') ?: $config->getOption('filter_field') ?: $form->getName();

        $event->addOrderBy([
            $sortField => $direction
        ], $form->getConfig()->getOption('sort_order', 0));
    }
}