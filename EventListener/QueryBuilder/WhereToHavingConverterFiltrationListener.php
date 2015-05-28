<?php


namespace ITE\FiltrationBundle\EventListener\QueryBuilder;

use ITE\FiltrationBundle\Event\FiltrationEvent;
use ITE\FiltrationBundle\Doctrine\Common\Collections\Criteria;
use ITE\FiltrationBundle\EventListener\AbstractFiltrationListener;

/**
 * Class WhereToHavingConverterFiltrationListener
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class WhereToHavingConverterFiltrationListener extends AbstractFiltrationListener
{
    /**
     * @param FiltrationEvent $event
     */
    public function filter(FiltrationEvent $event)
    {
        $form = $event->getForm();

        if (!$form->getConfig()->getOption('filter_aggregate')) {
            return;
        }

        if (!$criteria = $event->getCriteria()) {
            return;
        }

        $newCriteria = new Criteria();
        $newCriteria->andHaving($criteria->getWhereExpression());

        $event->setCriteria($newCriteria);
    }

}