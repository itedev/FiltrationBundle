<?php


namespace ITE\FiltrationBundle\Filtration\Templating;

/**
 * Class SimpleFormatter
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class SimpleFormatter implements FormatterInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var callable
     */
    private $callable;

    /**
     * @var array
     */
    private $options;

    /**
     * SimpleFormatter constructor.
     *
     * @param string   $name
     * @param callable $callable
     * @param array    $options
     */
    public function __construct($name, $callable, $options = [])
    {
        $this->name     = $name;
        $this->callable = $callable;
        $this->options = array_merge([
            'needs_context' => false,
            'needs_data' => false,
        ], $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function getCallable()
    {
        return $this->callable;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->options;
    }
}
