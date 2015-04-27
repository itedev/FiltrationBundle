<?php


namespace ITE\FiltrationBundle\Filtration;

/**
 * Interface FiltrationAwareInterface
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
interface FiltrationAwareInterface {

    /**
     * @param FiltrationManager $filtrationManager
     * @return mixed
     */
    public function setFiltrationManager(FiltrationManager $filtrationManager);

}