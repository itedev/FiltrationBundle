<?php


namespace ITE\FiltrationBundle\EventListener;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Expr\Value;
use ITE\FiltrationBundle\Event\FiltrationEvent;

/**
 * Class TextFiltrationListener
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class TextFiltrationListener extends AbstractFiltrationListener
{
    /**
     * @param FiltrationEvent $event
     * @return mixed
     */
    public function filter(FiltrationEvent $event)
    {
        $form = $event->getForm();
        $target = $event->getTarget();

        if (!$this->supportsParentType($form, 'text')) {
            return;
        }

        if (!$data = $form->getData()) {
            return;
        }

        $criteria = Criteria::create();

        if ($form->getConfig()->getOption('filter_type') == 'contains') {
            $criteria->andWhere(new Comparison($event->getFieldName(), Comparison::CONTAINS, new Value($data)));
        } else {
            $criteria->andWhere(new Comparison($event->getFieldName(), Comparison::EQ, new Value($data)));
        }

        $event->setTarget($this->matchCriteria($target, $criteria));
    }

}