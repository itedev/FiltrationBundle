<?php


namespace ITE\FiltrationBundle\EventListener;

use ITE\FiltrationBundle\Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Expr\Value;
use ITE\FiltrationBundle\Doctrine\Common\Collections\ArrayCollection;
use ITE\FiltrationBundle\Event\FiltrationEvent;
use ITE\FiltrationBundle\Doctrine\Common\Collections\Expr\Comparison as OverridenComparison;

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
        $form   = $event->getForm();
        $target = $event->getTarget();

        if (!$this->supportsParentType($form, 'text')) {
            return;
        }

        if (!$data = $form->getData()) {
            return;
        }

        $criteria = Criteria::create();
        if ($form->getConfig()->getOption('filter_type') == 'contains') {
            if ($form->getConfig()->getOption('matching_type') == 'case_insensitive') {
                if ($target instanceof \Doctrine\Common\Collections\ArrayCollection) {
                    $target = $target->toArray();
                }
                $target = new ArrayCollection($target);
                $event->setTarget($target);

                $criteria->andWhere(
                    new Comparison(
                        $event->getFieldName(),
                        OverridenComparison::CONTAINS_CASE_INSENSITIVE,
                        new Value($data)
                    )
                );
            } else {
                $criteria->andWhere(new Comparison($event->getFieldName(), Comparison::CONTAINS, new Value($data)));
            }
        } else {
            $criteria->andWhere(new Comparison($event->getFieldName(), Comparison::EQ, new Value($data)));
        }

        $event->setCriteria($criteria);
    }
}