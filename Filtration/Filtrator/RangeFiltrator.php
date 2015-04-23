<?php


namespace ITE\FiltrationBundle\Filtration\Filtrator;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Expr\Value;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormInterface;

/**
 * Class RangeFiltrator
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class RangeFiltrator extends AbstractFiltrator
{
    /**
     * @param FormInterface $form
     * @return bool
     */
    public function supports(FormInterface $form)
    {
        return $this->supportsType($form, 'range_filter');
    }

    /**
     * @param ArrayCollection|QueryBuilder $target
     * @param FormInterface $form
     * @return ArrayCollection|QueryBuilder
     */
    public function filter($target, FormInterface $form)
    {
        $from = $form->getData()['from'];
        $to = $form->getData()['to'];

        $criteria = Criteria::create();
        $criteria->andWhere(new Comparison($form->getName(), Comparison::GTE, new Value($from)));
        $criteria->andWhere(new Comparison($form->getName(), Comparison::LTE, new Value($to)));

        return $this->matchCriteria($target, $criteria);
    }

} 