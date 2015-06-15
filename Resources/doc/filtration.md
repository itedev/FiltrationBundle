# Overview

For now, ITEFiltrationBundle supports of filtering/sorting only QueryBuilder or ArrayCollection data.
But it's possible to implement filtering of other data types, and this will be described below.

## Configure Filter

`
Note: all described below will be example of [TableFilter](TableFilter) implementation, such as it's the most powerful
and useful. Of course, you can use own filter types.
`

Bundle provides 2 default filter classes: AbstractFilter (use it to make your own filter rendering, etc.) 
and TableFilter (well configured already ready to use filter in the head of a table.) 

There are a few steps for make filtration working with your data

### Creating Filter class

Your class should implement FilterInterface, but if you are using abstract/table filter, you just need to extend one of
these classes:

```php

// src/Acme/DemoBundle/Filter/FooFilter

use ITE\FiltrationBundle\Filtration\Filter\TableFilter;
use Acme\DemoBundle\Form\Filter\FooFilterType;

class FooFilter extends TableFilter
{
        /**
         * {@inheritdoc}
         */
        public function getFilterForm(FormFactoryInterface $formFactory)
        {
            return $formFactory->create(new FooFilterType());
        }
        
        /**
         * {@inheritdoc}
         */
        public function getName()
        {
            return 'referrer';
        }
}

```