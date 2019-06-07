<?php

namespace ITE\FiltrationBundle\Doctrine\ODM\MongoDB\Query;

use Doctrine\Common\Collections\Expr\Value;
use \Doctrine\ODM\MongoDB\Query\QueryExpressionVisitor AS BaseExpressionVisitor;

class QueryExpressionVisitor extends BaseExpressionVisitor
{
    public function walkValue(Value $value)
    {
        $val = $value->getValue();

        return $this->normalize($val);
    }

    /**
     * @param $val
     *
     * @return mixed
     */
    private function normalize($val)
    {
        if (is_array($val)) {
            foreach ($val as &$item) {
                $item = $this->normalize($item);
            }
        } elseif (is_object($val)) {
            if ($val instanceof \DateTime) {
                //...
            } else {
                return $val->getId();
            }
        }

        return $val;
    }
}