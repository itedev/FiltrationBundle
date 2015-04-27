<?php


namespace ITE\FiltrationBundle\Filtration\Filtrator;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Expr\Value;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormInterface;

/**
 * Class TextFiltrator
 * @package ITE\FiltrationBundle\Filtration\Filtrator
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class TextFiltrator extends AbstractFiltrator
{
    /**
     * @param FormInterface $form
     * @return bool
     */
    public function supports(FormInterface $form)
    {
        return $this->supportsType($form, 'text');
    }

    /**
     * @inheritdoc
     */
    public function filter($target, FormInterface $form, $fieldName = null)
    {
        if (!$data = $form->getData()) {
            return $target;
        }
        $fieldName = $fieldName ?: $form->getName();

        $criteria = Criteria::create();

        if ($form->getConfig()->getOption('filter_type') == 'contains') {
           $criteria->andWhere(new Comparison($fieldName, Comparison::CONTAINS, new Value($data)));
        } else {
            $criteria->andWhere(new Comparison($fieldName, Comparison::EQ, new Value($data)));
        }

        return $this->matchCriteria($target, $criteria);
    }

} 