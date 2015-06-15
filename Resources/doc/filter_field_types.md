# Filter field types

There are some useful form types was implemented to help with easier filter processing.

### callback_filter

Can be used for manual addin criteria to the target.

Example:

```php

// src/Acme/DemoBundle/Form/Filter/FooFilterType

//...
use ITE\FiltrationBundle\Event\CallbackFilterEvent
//...

 /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'callback_filter', [
                            'label' => false,
                            'type' => 'text',
                            'options' => [],
                            'callback' => function (CallbackFilterEvent $event) {
                                $criteria = Criteria::create();
                                $form = $event->getForm(); // this will be the child form.
                                $data = $form->getData();
                                $criteria->andWhere(new Comparison($event->getFieldName(), Comparison::EQ, new Value($data)));
                                
                                $event->setCriteria($criteria);
                            }
            ]);
    }
            
```
