# Filter form options

The filter form has some useful options for change/extand the behaviour of filtration

### filter_form

| Option | Value                 |
|---------------|-----------------------|
| Is optional | true                 |
| Allowed values | bool                 |
| Description | Mark that form is actually the filter form. Applies only to the root form.                 |

Example:

```php
// src/Acme/DemoBundle/Form/Filter/FooFilterType

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'filter_form' => true,
        ]);
    }
```

### filter_field

| Option | Value                 |
|---------------|-----------------------|
| Is optional | true                 |
| Allowed values | string                 |
| Description | Specifies the raw field name, that will be passed to target object (QueryBuidler/ArrayCollection etc.)               |

Example:

```php
// src/Acme/DemoBundle/Form/Filter/FooFilterType

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
              'filter_field' => 'f.name' // `f` is the alias of the entity that has a `name` field
            ]); 
    }
```

### filter_aggregate

| Option | Value                 |
|---------------|-----------------------|
| Is optional | true                 |
| Allowed values | bool                 |
| Description | Specifies that the field is the aggregated field, so `HAVING` expression will be used instead of `WHERE`              |

Example:

```php
// src/Acme/DemoBundle/Form/Filter/FooFilterType

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
              'filter_aggregate' => true
            ]); 
    }
```

# Text type extension

For configuring text filtering, there are additional options was implemented for form field type `text`:

### filter_type

| Option | Value                 |
|---------------|-----------------------|
| Is optional | true                 |
| Default value | `contains`                 |
| Allowed values | `contains`, `equals`                 |
| Description | Specifies how filed will be matched in the target. So, for example, for QueryBuilder for `equals`, `=` operator will be used and `LIKE` for `contains`. For ArrayCollection `==` and `strpos` will be used respectively              |

Example:

```php
// src/Acme/DemoBundle/Form/Filter/FooFilterType

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
              'filter_type' => 'equals'
            ]); 
    }
```

### matching_type

| Option | Value                 |
|---------------|-----------------------|
| Is optional | true                 |
| Default value | `case_sensitive`                 |
| Allowed values | `case_sensitive`, `case_insensitive`                 |
| Description | Using only for matching ArrayCollection              |

Example:

```php
// src/Acme/DemoBundle/Form/Filter/FooFilterType

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', [
              'matching_type' => 'case_insensitive'
            ]); 
    }
```
