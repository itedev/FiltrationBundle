<?php


namespace ITE\FiltrationBundle\EventListener\QueryBuilder;


use Doctrine\ORM\QueryBuilder as ORMQueryBuilder;
use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;
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
        if (!($event->getTarget() instanceof ORMQueryBuilder) && !($event->getTarget() instanceof DBALQueryBuilder)) {
            return;
        }

        $fieldName = $event->getFieldName();
        $form = $event->getForm();

        if ($form->getConfig()->getOption('filter_field') || $form->getConfig()->getOption('sort_field')) {
            $fieldName = $form->getConfig()->getOption('filter_field') ?: $form->getConfig()->getOption('sort_field');
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