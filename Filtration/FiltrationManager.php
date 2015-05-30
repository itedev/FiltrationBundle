<?php

namespace ITE\FiltrationBundle\Filtration;

use ITE\FiltrationBundle\Event\FiltrationEvent;
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
    public function filter($target, $filter)
    {
        if (is_string($filter)) {
            $filter = $this->getFilter($filter);
        }

        $form = $this->getFilterForm($filter->getName());
        if ($form->isValid()) {
            foreach ($form as $child) {
                $target = $this->filterPart($child, $target, $filter);
            }
        }

        return $target;
    }

    /**
     * @param FormInterface   $form
     * @param mixed           $target
     * @param FilterInterface $filter
     * @return \Doctrine\Common\Collections\ArrayCollection|\Doctrine\ORM\QueryBuilder
     */
    private function filterPart(FormInterface $form, $target, FilterInterface $filter)
    {
        $event = new FiltrationEvent($form, $target);
        $this->eventDispatcher->dispatch(FiltrationEvent::EVENT_NAME, $event);

        if ($event->getCriteria()) {
            foreach ($this->handlers as $handler) {
                if ($handler->supports($target)) {
                    $event->setTarget($handler->handle($target, $event->getCriteria()));
                }
            }
        }

        if ($event->isTargetModified()) {
            $filter->markFieldModified($form->getName());
        }

        return $event->getTarget();
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