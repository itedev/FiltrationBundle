<?php


namespace ITE\FiltrationBundle\Filtration;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class FiltrationManager
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class FiltrationManager
{
    /**
     * @var FiltratorInterface[]
     */
    private $filtrators = [];

    /**
     * @var FilterInterface[]
     */
    private $filters = [];

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    function __construct($formFactory, $requestStack)
    {
        $this->formFactory = $formFactory;
        $this->requestStack = $requestStack;
    }


    /**
     * @param mixed $target
     * @param string|FilterInterface $filter
     * @return mixed
     */
    public function filter($target, $filter)
    {
        if (is_string($filter)) {
            $filter = $this->getFilter($filter);
        }

        $form = $filter->getFilterForm($this->formFactory);
        $request = $this->requestStack->getCurrentRequest();

        $form->handleRequest($request);

        if ($form->isValid()) {
            foreach ($form as $child) {
                $target = $this->filterPart($child, $target);
            }
        }

        return $target;
    }

    /**
     * @param FormInterface $form
     * @param mixed $data
     * @return \Doctrine\Common\Collections\ArrayCollection|\Doctrine\ORM\QueryBuilder
     */
    private function filterPart($form, $data)
    {
        foreach ($this->filtrators as $filtrator) {
            if ($filtrator->supports($form)) {
               return $filtrator->filter($data, $form);
            }
        }

        return $data;
    }

    /**
     * @param $name
     * @return \ITE\FiltrationBundle\Filtration\FilterInterface
     */
    public function getFilter($name)
    {
        if (!isset($this->filters[$name])) {
            throw new \InvalidArgumentException(sprintf('Filter "%s" was not found.', $name));
        }

        return $this->filters[$name];
    }

    /**
     * @param $name
     * @return FormInterface
     */
    public function getFilterForm($name)
    {
        $filter = $this->getFilter($name);

        $form = $filter->getFilterForm($this->formFactory);
        $form->handleRequest($this->requestStack->getCurrentRequest());

        return $form;
    }

    /**
     * @param FiltratorInterface $filtrator
     */
    public function addFiltrator(FiltratorInterface $filtrator)
    {
        if ($filtrator instanceof FiltrationAwareInterface) {
            $filtrator->setFiltrationManager($this);
        }

        $this->filtrators []= $filtrator;
    }

    /**
     * @param FilterInterface $filter
     */
    public function addFilter(FilterInterface $filter)
    {
        if (isset($this->filters[$filter->getName()])) {
            throw new \InvalidArgumentException(sprintf('Filter "%s" has been already registered.', $filter->getName()));
        }

        if ($filter instanceof FiltrationAwareInterface) {
            $filter->setFiltrationManager($this);
        }

        $this->filters[$filter->getName()] = $filter;
    }

    /**
     * @return FiltratorInterface[]
     */
    public function getFiltrators()
    {
        return $this->filtrators;
    }

    /**
     * @return FilterInterface[]
     */
    public function getFilters()
    {
        return $this->filters;
    }
} 