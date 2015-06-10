<?php


namespace ITE\FiltrationBundle\Sorting;

use ITE\FormBundle\Form\FormInterface;
use Symfony\Component\Form\FormView;
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
    public function generateFormSortingUrl(FormInterface $form, $direction)
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
     * Generate an URL for sorting
     *
     * @param FormView $form
     * @param               $direction
     * @return string
     */
    public function generateFormViewSortingUrl(FormView $form, $direction)
    {
        $request = $this->requestStack->getMasterRequest();
        $route = $request->attributes->get('_route');
        $query = $request->query->all();
        $accessor = PropertyAccess::createPropertyAccessor();


        if (!isset($form->vars['sort_multiple']) || (isset($form->vars['sort_multiple']) && !$form->vars['sort_multiple'])) {
            // we need to remove all other orderings such as sort is not multiple.
            $parent = $form->parent;
            /** @var FormView $child */
            foreach ($parent as $child) {
                if (isset($child->vars['ite_sort']) && $child->vars['ite_sort']) {
                    $accessor->setValue($query, $this->getPropertyPath($child), null);
                }
            }
        }

        $accessor->setValue($query, $this->getPropertyPath($form), $direction);

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
        $request = $this->requestStack->getMasterRequest();
        $route = $request->attributes->get('_route');
        $query = $request->query->all();
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

    /**
     * @param FormView $formView
     * @return mixed
     */
    private function getPropertyPath(FormView $formView)
    {
        $root = $formView;

        while ($root->parent) {
            $root = $root->parent;
        }

        $propertyPath = str_replace($formView->vars['name'].'][filter]', $formView->vars['name'].'][sort]', $formView->vars['full_name']);
        $propertyPath = str_replace($root->vars['name'], '['.$root->vars['name'].']', $propertyPath);

        return $propertyPath;
    }
}