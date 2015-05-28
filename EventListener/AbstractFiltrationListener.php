<?php


namespace ITE\FiltrationBundle\EventListener;

use Symfony\Component\Form\FormInterface;

/**
 * Class AbstractFiltrationListener
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
abstract class AbstractFiltrationListener implements FiltrationListenerInterface
{
    /**
     * @param FormInterface $form
     * @param string        $typeName
     * @return bool
     */
    protected function supportsType(FormInterface $form, $typeName)
    {
        return $form->getConfig()->getType()->getName() === $typeName;
    }

    /**
     * @param FormInterface $form
     * @param array         $types
     * @return bool
     */
    protected function supportsTypes(FormInterface $form, $types)
    {
        return in_array($form->getConfig()->getType()->getName(), $types);
    }

    /**
     * @param FormInterface $form
     * @param string        $type
     * @return bool
     */
    protected function supportsParentType(FormInterface $form, $type)
    {
        $root = $form->getConfig()->getType();

        while (null !== $root->getParent()) {
            if ($type === $root->getName()) {
                return true;
            }
            $root = $root->getParent();
        }

        return false;
    }
}