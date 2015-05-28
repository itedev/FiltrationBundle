<?php


namespace ITE\FiltrationBundle\Filtration\Handler;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * Class ArrayCollectionHandler
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class ArrayCollectionHandler implements HandlerInterface
{
    /**
     * Handle target with a given criteria.
     *
     * @param  array|ArrayCollection    $target   The target to handle
     * @param  Criteria $criteria Criteria to handle with
     * @return mixed    Modified $target
     */
    public function handle($target, Criteria $criteria)
    {
        if (is_array($target)) {
            $target = new ArrayCollection($target);
        }

        return $target->matching($criteria);
    }

    /**
     * Checks that given target is able to be handled.
     *
     * @param $target
     * @return bool
     */
    public function supports($target)
    {
        return is_array($target) || $target instanceof ArrayCollection;
    }

}