<?php


namespace ITE\FiltrationBundle\Event;


use Doctrine\Common\Collections\ArrayCollection;
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
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName ?: $this->form->getName();
    }
}