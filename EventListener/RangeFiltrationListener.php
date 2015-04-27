<?php


namespace ITE\FiltrationBundle\EventListener;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Expr\Value;
use ITE\FiltrationBundle\Event\FiltrationEvent;

/**
 * Class RangeFiltrationListener
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class RangeFiltrationListener extends AbstractFiltrationListener
{
    /**
     * @param FiltrationEvent $event
     * @return mixed
     */
    public function filter(FiltrationEvent $event)
    {
        $form = $event->getForm();
        $target = $event->getTarget();

        if (!$this->supportsParentType($form, 'range_filter')) {
            return;
        }

        if (!$data = $form->getData()) {
            return;
        }

        $from = $form->getData()['from'];
        $to = $form->getData()['to'];

        $criteria = Criteria::create();
        $criteria->andWhere(new Comparison($event->getFieldName(), Comparison::GTE, new Value($from)));
        $criteria->andWhere(new Comparison($event->getFieldName(), Comparison::LTE, new Value($to)));

        $event->setTarget($this->matchCriteria($target, $criteria));
    }

}