<?php

namespace ITE\FiltrationBundle\Filtration;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormInterface;

/**
 * Interface FiltratorInterface
 */
interface FiltratorInterface
{
    /**
     * @param FormInterface $form
     * @return bool
     */
    public function supports(FormInterface $form);

    /**
     * @param ArrayCollection|QueryBuilder $target
     * @param FormInterface                $form
     * @param string|null                  $fieldName
     * @return ArrayCollection|QueryBuilder
     */
    public function filter($target, FormInterface $form, $fieldName = null);
} 