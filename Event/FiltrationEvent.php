<?php


namespace ITE\FiltrationBundle\Event;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\FormInterface;

/**
 * Class FiltrationEvent
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class FiltrationEvent extends Event
{
    const EVENT_NAME = 'ite_filtration.filter';

    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var ArrayCollection|QueryBuilder
     */
    private $target;
    /**
     * @var null
     */
    private $fieldName;

    /**
     * @var Criteria
     */
    private $criteria;

    /**
     * @var bool
     */
    protected $targetModified = false;

    /**
     * @param FormInterface                $form
     * @param ArrayCollection|QueryBuilder $target
     * @param string|null                  $fieldName
     */
    public function __construct(FormInterface $form, $target, $fieldName = null)
    {
        $this->form      = $form;
        $this->target    = $target;
        $this->fieldName = $fieldName;
    }

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @return ArrayCollection|QueryBuilder
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param ArrayCollection|QueryBuilder $target
     * @return $this
     */
    public function setTarget($target)
    {
        $this->target = $target;
        $this->targetModified = true;

        return $this;
    }

    /**
     * @return Criteria
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @param Criteria $criteria
     * @return $this
     */
    public function setCriteria(Criteria $criteria)
    {
        $this->criteria = $criteria;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isTargetModified()
    {
        return $this->targetModified;
    }

    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName ?: $this->form->getName();
    }

    /**
     * @param null $fieldName
     * @return $this
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;

        return $this;
    }
}