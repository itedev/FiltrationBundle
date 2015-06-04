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

        $value = $this->walkValue($comparison->getValue());
        $this->parameters[] = new Parameter($parameterName, $value);

        switch ($comparison->getOperator()) {
            case Comparison::EQ;
            case Comparison::NEQ;
            case Comparison::IS;
                if ($value === null) {
                    array_pop($this->parameters);

                    return sprintf(
                        '%s %s %s',
                        $comparison->getField(),
                        $comparison->getOperator() === Comparison::NEQ ? 'IS NOT' : 'IS',
                        'NULL'
                    );
                }
            case Comparison::GT;
            case Comparison::GTE;
            case Comparison::LT;
            case Comparison::LTE;
                return sprintf('%s %s %s', $comparison->getField(), $comparison->getOperator(), $placeholder);
            case Comparison::IN;
            case Comparison::NIN;
                return sprintf('%s %s (%s)', $comparison->getField(), $comparison->getOperator(), $placeholder);
            case Comparison::CONTAINS;
                $value = '%'.$this->walkValue($comparison->getValue()).'%';
                $this->parameters[count($this->parameters) - 1] = new Parameter($parameterName, $value);
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