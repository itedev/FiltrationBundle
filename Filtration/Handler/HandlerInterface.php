<?php


namespace ITE\FiltrationBundle\Filtration\Handler;

use Doctrine\Common\Collections\Criteria;

/**
 * Interface HandlerInterface
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
interface HandlerInterface
{
    /**
     * Handle target with a given criteria.
     *
     * @param  mixed    $target The target to handle
     * @param  Criteria $criteria Criteria to handle with
     * @return mixed    Modified $target
     */
    public function handle($target, Criteria $criteria);

    /**
     * Checks that given target is able to be handled.
     *
     * @param $target
     * @return bool
     */
    public function supports($target);
}