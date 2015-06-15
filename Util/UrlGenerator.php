<?php


namespace ITE\FiltrationBundle\Util;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class UrlGenerator
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class UrlGenerator implements UrlGeneratorInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * Public constructor
     *
     * @param RouterInterface $router
     * @param RequestStack    $requestStack
     */
    public function __construct(RouterInterface $router, RequestStack $requestStack)
    {
        $this->router       = $router;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function getResetFilterFieldUrl($form)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $request  = $this->requestStack->getMasterRequest();
        $route    = $request->attributes->get('_route');
        $query    = $request->query->all();

        $propertyPath = $form instanceof FormInterface ? $form->getPropertyPath() : $this->getPropertyPath($form);
        $accessor->setValue($query, $propertyPath, null);

        return $this->router->generate($route, $query);
    }

    /**
     * {@inheritdoc}
     */
    public function getResetFilterUrl($form)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $request  = $this->requestStack->getMasterRequest();
        $route    = $request->attributes->get('_route');
        $query    = $request->query->all();

        $form = $this->getParent($form);
        $name = $form instanceof FormInterface ? $form->getName() : $form->vars['name'];
        $accessor->setValue($query, sprintf('[%s]', $name), null);

        return $this->router->generate($route, $query);
    }

    /**
     * {@inheritdoc}
     */
    public function getSortFieldUrl($form, $direction)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $request  = $this->requestStack->getMasterRequest();
        $route    = $request->attributes->get('_route');
        $query    = $request->query->all();

        $sortField = $this->getSortField($form);
        $propertyPath = $sortField instanceof FormInterface ? $sortField->getPropertyPath() : $this->getPropertyPath($sortField);
        $accessor->setValue($query, $propertyPath, $direction);

        return $this->router->generate($route, $query);
    }

    /**
     * {@inheritdoc}
     */
    public function getResetSortFieldUrl($form)
    {
        return $this->getSortFieldUrl($form, null);
    }

    /**
     * {@inheritdoc}
     */
    public function getResetSortUrl($form)
    {
        $request  = $this->requestStack->getMasterRequest();
        $route    = $request->attributes->get('_route');
        $query    = $request->query->all();

        $form = $this->getParent($form);
        $name = $form instanceof FormInterface ? $form->getName() : $form->vars['name'];

        if (isset($query[$name])) {
            foreach ($query[$name] as $key => $value) {
                if (strpos($key, UrlGeneratorInterface::SORT_FIELD_PREFIX) === 0) {
                    unset($query[$name][$key]);
                }
            }

        }

        return $this->router->generate($route, $query);
    }

    /**
     * @param $form
     * @return mixed|null|FormInterface|FormView
     */
    private function getSortField($form)
    {
        $rootForm = $this->getParent($form);

        if ($form instanceof FormView) {
            return isset($rootForm[UrlGeneratorInterface::SORT_FIELD_PREFIX.$form->vars['name']]) ? $rootForm[UrlGeneratorInterface::SORT_FIELD_PREFIX.$form->vars['name']] : null;
        } elseif ($form instanceof FormInterface) {
            return $form->has(UrlGeneratorInterface::SORT_FIELD_PREFIX.$form->getName()) ? $form->get(UrlGeneratorInterface::SORT_FIELD_PREFIX.$form->getName()) : null;
        }

        throw new \InvalidArgumentException('Form should be instance of FormInterface of FormView');
    }

    /**
     * @param $form
     * @return null|FormInterface|FormView
     */
    private function getParent($form)
    {
        if ($form instanceof FormView) {
            $root = $form;

            while ($root->parent) {
                $root = $root->parent;
            }

            return $root;
        } elseif ($form instanceof FormInterface) {
            $root = $form;

            while ($root->getParent()) {
                $root = $root->getParent();
            }

            return $root;
        }

        throw new \InvalidArgumentException('Form should be instance of FormInterface of FormView');
    }

    /**
     * @param FormView $formView
     * @return string
     */
    private function getPropertyPath(FormView $formView)
    {
        $parent = $formView->parent;

        return sprintf('[%s][%s]', $parent->vars['name'], $formView->vars['name']);
    }
}