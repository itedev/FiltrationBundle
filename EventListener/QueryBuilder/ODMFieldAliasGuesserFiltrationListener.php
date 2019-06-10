<?php

namespace ITE\FiltrationBundle\EventListener\QueryBuilder;

use Doctrine\ODM\MongoDB\Query\Builder;
use ITE\FiltrationBundle\Event\FiltrationEvent;
use ITE\FiltrationBundle\EventListener\AbstractFiltrationListener;

/**
 * Class ODMFieldAliasGuesserFiltrationListener
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class ODMFieldAliasGuesserFiltrationListener extends AbstractFiltrationListener
{
    /**
     * @param FiltrationEvent $event
     */
    public function filter(FiltrationEvent $event)
    {
        if (!($event->getTarget() instanceof Builder)) {
            return;
        }

        $fieldName = $event->getFieldName();
        $form = $event->getForm();

        if ($form->getConfig()->getOption('filter_field') || $form->getConfig()->getOption('sort_field')) {
            $fieldName = $form->getConfig()->getOption('filter_field') ?: $form->getConfig()->getOption('sort_field');
        }

        $event->setFieldName($fieldName);
    }
}
