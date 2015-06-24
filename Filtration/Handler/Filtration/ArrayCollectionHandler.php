<?php


namespace ITE\FiltrationBundle\Filtration\Handler\Filtration;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use ITE\FiltrationBundle\Filtration\Handler\HandlerInterface;

/**
 * Class ArrayCollectionHandler
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class ArrayCollectionHandler implements HandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function handle($target, Criteria $criteria)
    {
        if (is_array($target)) {
            $target = new ArrayCollection($target);
        }

        return $target->matching($criteria);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($target)
    {
        return is_array($target) || $target instanceof ArrayCollection;
    }

}