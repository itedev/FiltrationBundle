<?php


namespace ITE\FiltrationBundle\Sorting;

use ITE\FormBundle\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class UrlGenerator
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class UrlGenerator
{
    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @param RouterInterface $router
     * @param RequestStack    $requestStack
     */
    public function __construct(RouterInterface $router, RequestStack $requestStack)
    {
        $this->router = $router;
        $this->requestStack = $requestStack;
    }

    /**
     * Generate an URL for sorting
     *
     * @param FormInterface $form
     * @param               $direction
     * @return string
     */
    public function generateSortingUrl(FormInterface $form, $direction)
    {
        $route = $this->requestStack->getMasterRequest()->attributes->get('_route');
        $query = $this->requestStack->getMasterRequest()->query->all();
        $accessor = PropertyAccess::createPropertyAccessor();

        if (!$form->getConfig()->getOption('sort_multiple', false)) {
            // we need to remove all other orderings such as sort is not multiple.
            $parent = $form->getParent();
            /** @var FormInterface $child */
            foreach ($parent as $child) {
                if ($child->getConfig()->getOption('sort')) {
                    $accessor->setValue($query, $child->get('sort')->getPropertyPath(), null);
                }
            }
        }

        $accessor->setValue($query, $form->get('sort')->getPropertyPath(), $direction);

        return $this->router->generate($route, $query);
    }

    /**
     * Generate an URL for reset sorting on field/whole form.
     *
     * @param FormInterface $form
     * @param bool          $all
     * @return string
     */
    public function generateResetUrl(FormInterface $form, $all = false)
    {
        $route = $this->requestStack->getMasterRequest()->attributes->get('_route');
        $query = $this->requestStack->getMasterRequest()->query->all();
        $accessor = PropertyAccess::createPropertyAccessor();

        if ($all) {
            $parent = $form->getParent();
            /** @var FormInterface $child */
            foreach ($parent as $child) {
                if ($child->getConfig()->getOption('sort')) {
                    $accessor->setValue($query, $child->get('sort')->getPropertyPath(), null);
                }
            }
        } else {
            $accessor->setValue($query, $form->get('sort')->getPropertyPath(), null);
        }

        return $this->router->generate($route, $query);
    }
}