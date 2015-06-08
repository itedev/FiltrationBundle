<?php


namespace ITE\FiltrationBundle\Event;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\FormInterface;

/**
 * Class SortingEvent
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class SortingEvent extends Event
{
    /**
     * @var FormInterface
     */
    private $form;

    /**
     * @var ArrayCollection|QueryBuilder
     */
    private $target;

    /**
     * @var ArrayCollection
     */
    private $options;

    /**
     * @var array
     */
    private $orderings = [];

    /**
     * @param FormInterface                $form
     * @param ArrayCollection|QueryBuilder $target
     * @param array                        $options
     */
    public function __construct(FormInterface $form, $target, $options = [])
    {
        $this->form = $form;
        $this->target = $target;
        $this->options   = new ArrayCollection($options);
    }

    /**
     * Get form
     *
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Get options
     *
     * @return ArrayCollection
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Get target
     *
     * @return ArrayCollection|QueryBuilder
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Set target
     *
     * @param ArrayCollection|QueryBuilder $target
     * @return SortingEvent
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * Set orderings
     *
     * @param array $orderings
     * @return SortingEvent
     */
    public function setOrderings($orderings)
    {
        $this->orderings = $orderings;

        return $this;
    }

    /**
     * @param array $orderBy
     * @param int   $priority
     */
    public function addOrderBy(array $orderBy, $priority = 0)
    {
        if (!isset($this->orderings[$priority])) {
            $this->orderings[$priority] = [];
        }

        $this->orderings[$priority] = array_merge($this->orderings[$priority], $orderBy);
    }

    /**
     * Get orderings
     *
     * @return array
     */
    public function getOrderings()
    {
        return $this->orderings;
    }

    /**
     * @param array $orderings
     * @return array
     */
    public static function getSorted(array $orderings)
    {
        $sorted = [];
        ksort($orderings);

        foreach ($orderings as $sortedOrderings) {
            $sorted = array_merge($sorted, $sortedOrderings);
        }

        return $sorted;
    }
}