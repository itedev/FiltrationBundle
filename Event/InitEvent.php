<?php


namespace ITE\FiltrationBundle\Event;

use ITE\FiltrationBundle\Filtration\Filter\AbstractFilter;
use ITE\FiltrationBundle\Filtration\FilterInterface;
use ITE\FiltrationBundle\Filtration\Result\FiltrationResultInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Class InitEvent
 *
 * @author jeka <jekamozg@mail.ru>
 */
class InitEvent extends FiltrationEvent
{
    /**
     * @var FiltrationResultInterface $filtrationResult
     */
    private $filtrationResult;

    /**
     * InitEvent constructor.
     *
     * @param FiltrationResultInterface $filtrationResult
     */
    public function __construct(FiltrationResultInterface $filtrationResult)
    {
        parent::__construct($filtrationResult->getFilterForm(), $filtrationResult->getOriginalTarget(), $filtrationResult->getOptions());
        $this->filtrationResult = $filtrationResult;
    }

    /**
     * Get filtrationResult
     *
     * @return FiltrationResultInterface
     */
    public function getFiltrationResult()
    {
        return $this->filtrationResult;
    }
}
