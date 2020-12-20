<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Types\ArrayLiteral;
use Monkey\Object\ArrayObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;

final class EvalArrayLiteral
{
    public function __construct(private Evaluator $evaluator, private Environment $env)
    {
    }

    public function __invoke(ArrayLiteral $node): MonkeyObject
    {
        $elements = $this->evaluator->evalExpressions($node->elements(), $this->env);

        if (1 === $elements && $elements[0] instanceof ErrorObject) {
            return $elements[0];
        }

        return new ArrayObject($elements);
    }
}
