<?php


namespace ITE\FiltrationBundle\Doctrine\Common\Collections\Expr;

use Doctrine\Common\Collections\Expr\ClosureExpressionVisitor as BaseClosureExpressionVisitor;
use Doctrine\Common\Collections\Expr\Comparison as BaseComparison;
use ITE\FiltrationBundle\Doctrine\Common\Collections\Expr\Comparison as OverridenComparison;

/**
 * Class ClosureExpressionVisitor
 *
 * @author sam0delkin <t.samodelkin@gmail.com>
 */
class ClosureExpressionVisitor extends BaseClosureExpressionVisitor
{

    public function walkComparison(BaseComparison $comparison)
    {
        $field = $comparison->getField();
        $value = $comparison->getValue()->getValue();

        if ($comparison->getOperator() == OverridenComparison::CONTAINS_CASE_INSENSITIVE) {
            return function ($object) use ($field, $value) {
                return false !== stripos(ClosureExpressionVisitor::getObjectFieldValue($object, $field), $value);
            };
        } else {
            return parent::walkComparison($comparison);
        }
    }

}