<?php


namespace ITE\FiltrationBundle\EventListener;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use ITE\FiltrationBundle\Event\FiltrationEvent;

/**
 * Class ChoiceFiltrationListener
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class ChoiceFiltrationListener extends AbstractFiltrationListener
{
    /**
     * @param FiltrationEvent $event
     * @return mixed
     */
    public function filter(FiltrationEvent $event)
    {
        $form = $event->getForm();
        $target = $event->getTarget();

        if (!$this->supportsParentType($form, 'choice')) {
            return;
        }

        if (!$data = $form->getData()) {
            return;
        }

        if (!is_array($data)) {
            $data = [$data];
        }

        $criteria = Criteria::create();
        $criteria->andWhere(new Comparison($event->getFieldName(), Comparison::IN, $data));

        $event->setTarget($this->matchCriteria($target, $criteria));
    }

}