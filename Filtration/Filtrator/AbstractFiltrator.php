<?php


namespace ITE\FiltrationBundle\Filtration\Filtrator;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use ITE\FiltrationBundle\Filtration\FiltratorInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Class AbstractFiltrator
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
abstract class AbstractFiltrator implements FiltratorInterface
{
    /**
     * @param FormInterface $form
     * @param $typeName
     * @return bool
     */
    protected function supportsType(FormInterface $form, $typeName)
    {
        return $form->getConfig()->getType()->getName() === $typeName;
    }

    /**
     * @param $target
     * @param Criteria $criteria
     */
    protected function matchCriteria($target, Criteria $criteria)
    {
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

            throw new \InvalidArgumentException(sprintf('Filter supports only "ArrayCollection" and "QueryBuilder", "%s" given', $type));
        }
    }
} 