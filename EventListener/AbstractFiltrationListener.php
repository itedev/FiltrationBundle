<?php

namespace ITE\FiltrationBundle\EventListener;

use ITE\Common\Util\ReflectionUtils;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormRegistryInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\ResolvedFormTypeFactoryInterface;
use Symfony\Component\Form\ResolvedFormTypeInterface;

/**
 * Class AbstractFiltrationListener
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
abstract class AbstractFiltrationListener implements FiltrationListenerInterface
{
    /**
     * @var FormRegistryInterface $registry
     */
    private $registry;

    /**
     * @var ResolvedFormTypeInterface $resolvedTypeFactory
     */
    private $resolvedTypeFactory;

    /**
     * @param FormInterface $form
     * @param string        $typeName
     * @return bool
     */
    protected function supportsType(FormInterface $form, $typeName)
    {
        return $this->getFilterFormType($form)->getName() === $typeName;
    }

    /**
     * @param FormInterface $form
     * @param array         $types
     * @return bool
     */
    protected function supportsTypes(FormInterface $form, $types)
    {
        return in_array($this->getFilterFormType($form)->getName(), $types);
    }

    /**
     * @param FormInterface $form
     * @param string        $type
     * @return bool
     */
    protected function supportsParentType(FormInterface $form, $type)
    {
        $root = $this->getFilterFormType($form);
        while (null !== $root->getParent()) {
            if ($type === $root->getName()) {
                return true;
            }
            $root = $root->getParent();
        }

        return false;
    }

    /**
     * @param FormInterface $form
     * @return ResolvedFormTypeInterface
     */
    protected function getFilterFormType(FormInterface $form)
    {
        if (null !== $filterFormType = $form->getConfig()->getOption('filter_form_type')) {
            $formFactory = $form->getConfig()->getFormFactory();
            /** @var FormRegistryInterface $registry */
            $this->registry = ReflectionUtils::getValue($formFactory, 'registry');
            /** @var ResolvedFormTypeInterface $resolvedTypeFactory */
            $this->resolvedTypeFactory = ReflectionUtils::getValue($formFactory, 'resolvedTypeFactory');

            if (is_string($filterFormType)) {
                return $this->registry->getType($filterFormType);
            } else {
                return $this->resolveType($filterFormType);
            }
        }

        return $form->getConfig()->getType();
    }

    /**
     * @param FormTypeInterface $type
     * @return ResolvedFormTypeInterface
     */
    private function resolveType(FormTypeInterface $type)
    {
        $parentType = $type->getParent();

        if ($parentType instanceof FormTypeInterface) {
            $parentType = $this->resolveType($parentType);
        } elseif (null !== $parentType) {
            $parentType = $this->registry->getType($parentType);
        }

        return $this->resolvedTypeFactory->createResolvedType(
            $type,
            [],
            $parentType
        );
    }
}
