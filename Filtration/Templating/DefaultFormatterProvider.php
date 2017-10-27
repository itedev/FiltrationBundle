<?php


namespace ITE\FiltrationBundle\Filtration\Templating;

use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class DefaultFormatterProvider
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class DefaultFormatterProvider implements FormatterProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getFormatters()
    {
        return [
            new SimpleFormatter('string', function ($value) {
                return $value instanceof \DateTime
                    ? $value->format('Y-m-d H:i:s')
                    : (string) $value;
            }),
            new SimpleFormatter('attribute', function ($value, $attrName) {
                $accessor = PropertyAccess::createPropertyAccessor();

                return $accessor->getValue($value, $attrName);
            }),
            new SimpleFormatter('number', function ($value) {
                return number_format($value);
            })
        ];
    }
}
