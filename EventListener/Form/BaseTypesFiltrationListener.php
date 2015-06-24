<?php


namespace ITE\FiltrationBundle\EventListener\Form;

use Doctrine\Common\Collections\Expr\Comparison;
use ITE\FiltrationBundle\Doctrine\Common\Collections\Criteria;
use ITE\FiltrationBundle\Event\FiltrationEvent;
use ITE\FiltrationBundle\EventListener\AbstractFiltrationListener;

/**
 * Class BaseTypesFiltrationListener
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class BaseTypesFiltrationListener extends AbstractFiltrationListener
{

    /**
     * {@inheritdoc}
     */
    public function filter(FiltrationEvent $event)
    {
        $form = $event->getForm();

        if (
               !$this->supportsParentType($form, 'money')
            && !$this->supportsParentType($form, 'number')
            && !$this->supportsParentType($form, 'percent')
            && !$this->supportsParentType($form, 'date')
            && !$this->supportsParentType($form, 'datetime')
            && !$this->supportsParentType($form, 'time')
            && !$this->supportsParentType($form, 'integer')
            && !$this->supportsParentType($form, 'checkbox')
        ) {
            return;
        }

        if (!($data = $form->getData())) {
            return;
        }

        $criteria = Criteria::create();
        $criteria->andWhere(new Comparison($event->getFieldName(), Comparison::EQ, $data));

        $event->setCriteria($criteria);
    }
}