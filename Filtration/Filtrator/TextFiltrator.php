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
        return $this->supportsType($form, 'text_filter');
    }

    /**
     * @param ArrayCollection|QueryBuilder $target
     * @param mixed $form
     * @return \Doctrine\Common\Collections\ArrayCollection|\Doctrine\ORM\QueryBuilder|void
     */
    public function filter($target, FormInterface $form)
    {
        if (!$data = $form->getData()) {
            return $target;
        }

        $criteria = Criteria::create();

        if ($form->getConfig()->getOption('filter_type') == 'contains') {
           $criteria->andWhere(new Comparison($form->getName(), Comparison::CONTAINS, new Value($data)));
        } else {
            $criteria->andWhere(new Comparison($form->getName(), Comparison::EQ, new Value($data)));
        }

        return $this->matchCriteria($target, $criteria);
    }

} 