<?php


namespace ITE\FiltrationBundle\EventListener;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Debug\Exception\UndefinedMethodException;
use Symfony\Component\Form\Extension\DataCollector\Proxy\ResolvedTypeDataCollectorProxy;
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

    /**
     * @param          $target
     * @param Criteria $criteria
     * @return \Doctrine\Common\Collections\Collection|QueryBuilder|static
     */
    protected function matchCriteria($target, Criteria $criteria)
    {
        if (is_array($target)) {
            $target = new ArrayCollection($target);
        }

        if ($target instanceof ArrayCollection) {
            return $target->matching($criteria);
        } elseif ($target instanceof QueryBuilder) {
            return $target->addCriteria($criteria);
        } else {
            if (is_object($target)) {
                $type = get_class($target);
            } else {
                $type = gettype($target);
            }

            throw new \InvalidArgumentException(
                sprintf('Filter supports only "ArrayCollection" and "QueryBuilder", "%s" given', $type)
            );
        }
    }
}