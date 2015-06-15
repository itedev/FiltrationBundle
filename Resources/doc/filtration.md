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

### Creating Filter FormType class

FiltrationBundle is using standart Symfony2 forms for filter rendering and sorting. So, each filter need a form type
that will represents the filter

```php

// src/Acme/DemoBundle/Form/Filter/FooFilterType

// ...
use Symfony\Component\Form\AbstractType;
// ...

class FooFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text');  // this is the filter row, so, for example, if you filtering an object of QueryBuilder, 
                                    // then the target entity should contain field `name`
   }
   
   /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'filter_form' => true, // this is requred for filtering
        ]);
    }
}

```

The filter form type is very easy to configure, and detailed configuration will be described below.

### Creating Filter class

Your class should implement FilterInterface, but if you are using abstract/table filter, you just need to extend one of
these classes:

```php

// src/Acme/DemoBundle/Filter/FooFilter

// ...
use ITE\FiltrationBundle\Filtration\Filter\TableFilter;
use Acme\DemoBundle\Form\Filter\FooFilterType;
// ...

class FooFilter extends TableFilter
{
        /**
         * {@inheritdoc}
         */
        public function getFilterForm(FormFactoryInterface $formFactory)
        {
            return $formFactory->create(new FooFilterType()); // return the form, based on filter field type, declared above.
        }
        
        /**
         * {@inheritdoc}
         */
        public function getName()
        {
            return 'foo'; // the filter name, that will be used to render the filter.
        }
        
        /**
        * {@inheritdoc}
        */
        public function getHeaders() // this is required only for the table filter.
        {
            return [
                'name'  => 'Name', // this column will be filterable, such as form has the key as child
                'title' => 'Title', // this column will be not filterable
            ];
            // note, you can use translations for header column labels
        }
        
        /**
        * {@inheritdoc}
        */
        protected function setOptions(OptionsResolver $resolver) // this is the setter of table options
        {
            $resolver->setDefaults([
                'table_class' => 'table',
            ]);
        }
}

```

After creating a class, you need to create a service, based on this class:

```yaml

acme_demo.ite_filtration.filter.foo:
    class: Acme\DemoBundle\Filter\FooFilter
    tags:
        - { name: ite_filtration.filter } # this tags add filter to the filtration registry

```

### Rendering filter in controller

After all steps done, you can simply filter data in controller and render filter in the template:

```php

// src/Acme/DemoBundle/Controller/FooController

// ...
/**
 * @Tempalte()
 */
public function indexAction()
{
        $target = $this->getDoctrine()->get('AcmeDemo:Foo')->getBarQueryBuilder();
        $target = $this->get('ite_filtrator')->filter(
                'foo', // the return value fof the `getName` method of filter class
                $target
        );
        
        // some other stuff, for example, pagination, etc.
        $target = $target->getQuery()->getResult();
        
        return ['target' => $target];
}

```

And the finally, you just need to render the filtered table in the view:

```twig

{# src/Acme/DemoBundle/Resources/views/Foo/index.html.twig #}

{% block content %}
        {{ ite_filtration_render('foo', {target: target}) }} 
        {# first parameter is the filter name #}
        {# second parameter is the context that will pe passed to filter tempalte. #}
        {# note: the `target` parameter is required #}
{% endblock %}

```

And that's all. After opening the page you will see the rendered table of entities, with filter columns.

For more information about filter configuring, see other parts of this documentation.
