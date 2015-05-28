<?php


namespace ITE\FiltrationBundle\Doctrine\Common\Collections;

use Doctrine\Common\Collections\Criteria as BaseCriteria;
use Doctrine\Common\Collections\Expr\CompositeExpression;
use Doctrine\Common\Collections\Expr\Expression;

/**
 * Class Criteria
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class Criteria extends BaseCriteria
{
    /**
     * @var \Doctrine\Common\Collections\Expr\Expression|null
     */
    protected $havingExpression;

    /**
     * Sets the having expression to evaluate when this Criteria is searched for.
     *
     * @param Expression $expression
     *
     * @return Criteria
     */
    public function having(Expression $expression)
    {
        $this->havingExpression = $expression;

        return $this;
    }

    /**
     * Appends the having expression to evaluate when this Criteria is searched for
     * using an AND with previous expression.
     *
     * @param Expression $expression
     *
     * @return Criteria
     */
    public function andHaving(Expression $expression)
    {
        if ($this->havingExpression === null) {
            return $this->having($expression);
        }

        $this->havingExpression = new CompositeExpression(CompositeExpression::TYPE_AND, array(
            $this->havingExpression, $expression
        ));

        return $this;
    }

    /**
     * Appends the having expression to evaluate when this Criteria is searched for
     * using an OR with previous expression.
     *
     * @param Expression $expression
     *
     * @return Criteria
     */
    public function orHaving(Expression $expression)
    {
        if ($this->havingExpression === null) {
            return $this->having($expression);
        }

        $this->havingExpression = new CompositeExpression(CompositeExpression::TYPE_OR, array(
            $this->havingExpression, $expression
        ));

        return $this;
    }

    /**
     * Gets the having expression attached to this Criteria.
     *
     * @return Expression|null
     */
    public function getHavingExpression()
    {
        return $this->havingExpression;
    }
}