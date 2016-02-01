<?php


namespace ITE\FiltrationBundle\EventListener\Form;

use Doctrine\Common\Collections\Collection;
use ITE\FiltrationBundle\Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use ITE\FiltrationBundle\Event\FiltrationEvent;
use ITE\FiltrationBundle\EventListener\AbstractFiltrationListener;

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

        if (!$this->supportsParentType($form, 'choice')) {
            return;
        }

        if (null === ($data = $form->getData())) {
            return;
        }

        if ($data instanceof Collection) {
            $data = $data->toArray();
        }

        if (!is_array($data)) {
            $data = [$data];
        }

        if (empty($data)) {
            return;
        }

        $criteria = Criteria::create();
        $criteria->andWhere(new Comparison($event->getFieldName(), Comparison::IN, $data));

        $event->setCriteria($criteria);
    }

}