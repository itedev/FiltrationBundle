<?php

namespace ITE\FiltrationBundle\Filtration\Templating;

/**
 * Class FormatterManager
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class FormatterManager
{
    /**
     * @var FormatterInterface[] $formatters
     */
    protected $formatters;

    /**
     * @param FormatterInterface $formatter
     */
    public function addFormatter(FormatterInterface $formatter)
    {
        $this->formatters[$formatter->getName()] = $formatter;
    }

    /**
     * @param FormatterProviderInterface $formatterProvider
     */
    public function addFormatterProvider(FormatterProviderInterface $formatterProvider)
    {
        foreach ($formatterProvider->getFormatters() as $formatter) {
            $this->addFormatter($formatter);
        }
    }

    /**
     * @param string $name
     *
     * @return FormatterInterface
     *
     * @throws \Exception
     */
    public function getFormatter($name)
    {
        if (!isset($this->formatters[$name])) {
            throw new \Exception(sprintf('Formatter "%s" is not registerd.', $formatterName));
        }

        return $this->formatters[$name];
    }

    /**
     * @param mixed  $value
     * @param string $formatterName
     * @param array  $formatterParameters
     *
     * @return mixed
     */
    public function format($value, $formatterName, $formatterParameters = [])
    {
        $formatter = $this->getFormatter($formatterName);

        $params = array_merge([$value], $formatterParameters);

        return call_user_func_array($formatter->getCallable(), $params);
    }
}
