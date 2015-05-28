<?php


namespace ITE\FiltrationBundle\EventListener\QueryBuilder;


use Doctrine\ORM\QueryBuilder;
use ITE\FiltrationBundle\Event\FiltrationEvent;
use ITE\FiltrationBundle\EventListener\AbstractFiltrationListener;

/**
 * Class FieldAliasGuesserFiltrationListener
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class FieldAliasGuesserFiltrationListener extends AbstractFiltrationListener
{
    /**
     * @param FiltrationEvent $event
     */
    public function filter(FiltrationEvent $event)
    {
        if (!($event->getTarget() instanceof QueryBuilder)) {
            return;
        }

        $fieldName = $event->getFieldName();
        $form = $event->getForm();

        if ($formFieldName = $form->getConfig()->getOption('filter_field')) {
            $fieldName = $formFieldName;
        } else {
            if ($form->getConfig()->getOption('filter_aggregate') === true) {
                return;
            }
            $rootAlias = $event->getTarget()->getRootAliases()[0];
            $fieldName = sprintf('%s.%s', $rootAlias, $fieldName);
        }

        $event->setFieldName($fieldName);
    }

}