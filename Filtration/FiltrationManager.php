<?php

namespace ITE\FiltrationBundle\Filtration;

use ITE\FiltrationBundle\Doctrine\Common\Collections\Criteria;
use ITE\FiltrationBundle\Event\FiltrationEvent;
use ITE\FiltrationBundle\Event\FiltrationEvents;
use ITE\FiltrationBundle\Event\SortingEvent;
use ITE\FiltrationBundle\Filtration\Handler\HandlerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class FiltrationManager
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class FiltrationManager implements FiltrationInterface
{
    /**
     * @var FilterInterface[]
     */
    protected $filters = [];

    /**
     * @var HandlerInterface[]
     */
    protected $handlers = [];

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @param FormFactoryInterface $formFactory
     * @param RequestStack $requestStack
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(FormFactoryInterface $formFactory, RequestStack $requestStack,
        EventDispatcherInterface $eventDispatcher)
    {
        $this->formFactory = $formFactory;
        $this->requestStack = $requestStack;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @inheritdoc
     */
    public function filter($target, $filter, array $options = [])
    {
        if (is_string($filter)) {
            $filter = $this->getFilter($filter);
        }

        $form = $this->getFilterForm($filter->getName());

        if ($form->isValid()) {
            $target = $this->doFilter($form, $target, $filter, $options);
            $target = $this->doSort($form, $target, $filter, $options);
        }

        return $target;
    }

    /**
     * @param FormInterface   $form
     * @param mixed           $target
     * @param FilterInterface $filter
     * @param array           $options
     *
     * @return \Doctrine\Common\Collections\ArrayCollection|\Doctrine\ORM\QueryBuilder
     */
    private function doFilter(FormInterface $form, $target, FilterInterface $filter, array $options)
    {
        $event = new FiltrationEvent($form, $target, $options);
        $this->eventDispatcher->dispatch(FiltrationEvents::BEFORE_FILTER, $event);
        $target = $event->getTarget();

        if ($event->isPropagationStopped()) {
            return $target;
        }

        foreach ($form as $child) {
            $target = $this->filterChild($child, $target, $filter, $options);
        }

        $event = new FiltrationEvent($form, $target, $options);
        $this->eventDispatcher->dispatch(FiltrationEvents::AFTER_FILTER, $event);
        $target = $event->getTarget();

        return $target;
    }

    /**
     * @param FormInterface   $form
     * @param mixed           $target
     * @param FilterInterface $filter
     * @param array           $options
     *
     * @return \Doctrine\Common\Collections\ArrayCollection|\Doctrine\ORM\QueryBuilder|mixed
     */
    private function doSort(FormInterface $form, $target, FilterInterface $filter, array $options)
    {
        $event = new SortingEvent($form, $target, $options);
        $this->eventDispatcher->dispatch(FiltrationEvents::BEFORE_SORT, $event);
        $target = $event->getTarget();
        $orderings = $event->getOrderings();

        if ($event->isPropagationStopped()) {
            if (!empty($orderings)) {
                $criteria = Criteria::create();
                $criteria->orderBy($orderings);
                $target = $this->handleCriteria($target, $criteria);
            }

            return $target;
        }

        foreach ($form as $child) {
            $event = new SortingEvent($child, $target, $options);
            $this->eventDispatcher->dispatch(FiltrationEvents::SORT, $event);

            if ($event->getOrderings()) {
                $orderings = array_merge($orderings, $event->getOrderings());
            }
        }

        $criteria = Criteria::create();
        $criteria->orderBy(SortingEvent::getSorted($orderings));
        $this->handleCriteria($target, $criteria);

        $event = new SortingEvent($form, $target, $options);
        $event->setOrderings($orderings);
        $this->eventDispatcher->dispatch(FiltrationEvents::AFTER_SORT, $event);
        $target = $event->getTarget();

        return $target;
    }

    /**
     * @param FormInterface   $form
     * @param mixed           $target
     * @param FilterInterface $filter
     * @param array           $options
     * @return \Doctrine\Common\Collections\ArrayCollection|\Doctrine\ORM\QueryBuilder
     */
    private function filterChild(FormInterface $form, $target, FilterInterface $filter, $options)
    {
        $event = new FiltrationEvent($form, $target, $filter->getOptions($options));
        $this->eventDispatcher->dispatch(FiltrationEvents::FILTER, $event);

        if ($event->getCriteria()) {
            $event->setTarget($this->handleCriteria($event->getTarget(), $event->getCriteria()));
        }

        if ($event->isTargetModified()) {
            $filter->markFieldModified($form->getName());
        }

        return $event->getTarget();
    }

    /**
     * @param  mixed   $target
     * @param Criteria $criteria
     * @return mixed
     */
    private function handleCriteria($target, Criteria $criteria)
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($target)) {
                $target = $handler->handle($target, $criteria);
            }
        }

        return $target;
    }

    /**
     * @inheritdoc
     */
    public function getFilter($name)
    {
        if (!isset($this->filters[$name])) {
            throw new \InvalidArgumentException(sprintf('Filter "%s" was not found.', $name));
        }

        return $this->filters[$name];
    }

    /**
     * @inheritdoc
     */
    public function getFilterForm($name)
    {
        $filter = $this->getFilter($name);
        $form = $filter->getFilterForm($this->formFactory);
        if (!$form->getConfig()->getOption('filter_form')) {
            throw new \LogicException('Filter form should have an option "filter_form" set to true.');
        }

        $request = $this->requestStack->getMasterRequest();
        if ($request->query->has($form->getName())) {
            $form->submit($request->query->get($form->getName()));
        }

        return $form;
    }

    /**
     * @inheritdoc
     */
    public function addFilter(FilterInterface $filter)
    {
        if (isset($this->filters[$filter->getName()])) {
            throw new \InvalidArgumentException(sprintf('Filter "%s" has been already registered.', $filter->getName()));
        }

        $this->filters[$filter->getName()] = $filter;
    }

    /**
     * @inheritdoc
     */
    public function addHandler(HandlerInterface $handler)
    {
        $this->handlers[] = $handler;
    }

    /**
     * @inheritdoc
     */
    public function getFilters()
    {
        return $this->filters;
    }
} 