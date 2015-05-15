<?php


namespace ITE\FiltrationBundle\Event;

use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Form\FormInterface;

/**
 * Class CallbackFilterEvent
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class CallbackFilterEvent extends FiltrationEvent
{
    /**
     * @var Criteria
     */
    private $criteria;

    /**
     * @inheritdoc
     */
    public function __construct(FormInterface $form, $target, $fieldName = null)
    {
        parent::__construct($form, $target, $fieldName);
        $this->criteria = Criteria::create();
    }

    /**
     * @return Criteria
     */
    public function getCriteria()
    {
        $this->targetModified = true;
        return $this->criteria;
    }
}