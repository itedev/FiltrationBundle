<?php

namespace ITE\FiltrationBundle\Event;

use ITE\FiltrationBundle\Filtration\Result\FiltrationResultInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class ResultEvent
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class ResultEvent extends Event
{
    /**
     * @var FiltrationResultInterface
     */
    private $result;

    /**
     * ResultEvent constructor.
     *
     * @param FiltrationResultInterface $result
     */
    public function __construct(FiltrationResultInterface $result)
    {
        $this->result = $result;
    }

    /**
     * Get result
     *
     * @return FiltrationResultInterface
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set result
     *
     * @param FiltrationResultInterface $result
     *
     * @return ResultEvent
     */
    public function setResult(FiltrationResultInterface $result)
    {
        $this->result = $result;

        return $this;
    }
}
