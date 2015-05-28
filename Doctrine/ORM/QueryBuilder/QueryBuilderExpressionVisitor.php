<?php


namespace ITE\FiltrationBundle\Doctrine\ORM\QueryBuilder;


use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\Common\Collections\Expr\CompositeExpression;
use Doctrine\Common\Collections\Expr\ExpressionVisitor;
use Doctrine\Common\Collections\Expr\Value;
use Doctrine\ORM\Query\Expr\Andx;
use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\Query\Parameter;

/**
 * Class QueryBuilderExpressionVisitor
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class QueryBuilderExpressionVisitor extends ExpressionVisitor
{
    /**
     * @var Parameter[]
     */
    private $parameters;


    /**
     * Converts a comparison expression into the target query language output.
     *
     * @param Comparison $comparison
     *
     * @return mixed
     */
    public function walkComparison(Comparison $comparison)
    {
        $parameterName = uniqid(str_replace('.', '_', $comparison->getField()));
        $placeholder = ':'.$parameterName;

        $this->parameters[] = new Parameter($parameterName, $this->walkValue($comparison->getValue()));

        switch ($comparison->getOperator()) {
            case Comparison::EQ;
            case Comparison::GT;
            case Comparison::GTE;
            case Comparison::IS;
            case Comparison::LT;
            case Comparison::LTE;
            case Comparison::NEQ;
                return sprintf('%s %s %s', $comparison->getField(), $comparison->getOperator(), $placeholder);
            case Comparison::IN;
            case Comparison::NIN;
                return sprintf('%s %s (%s)', $comparison->getField(), $comparison->getOperator(), $placeholder);
            case Comparison::CONTAINS;
                return sprintf('%s %s %s', $comparison->getField(), 'LIKE', $placeholder);
            default:
                return sprintf('%s %s %s', $comparison->getField(), $comparison->getOperator(), $placeholder);
        }

    }

    /**
     * Converts a value expression into the target query language part.
     *
     * @param Value $value
     *
     * @return mixed
     */
    public function walkValue(Value $value)
    {
        return $value->getValue();
    }

    /**
     * Converts a composite expression into the target query language output.
     *
     * @param CompositeExpression $expr
     *
     * @return mixed
     */
    public function walkCompositeExpression(CompositeExpression $expr)
    {
        $expressionList = array();

        foreach ($expr->getExpressionList() as $child) {
            $expressionList[] = $this->dispatch($child);
        }

        switch($expr->getType()) {
            case CompositeExpression::TYPE_AND:
                return new Andx($expressionList);

            case CompositeExpression::TYPE_OR:
                return new Orx($expressionList);

            default:
                throw new \RuntimeException("Unknown composite " . $expr->getType());
        }
    }

    /**
     * @return \Doctrine\ORM\Query\Parameter[]
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}