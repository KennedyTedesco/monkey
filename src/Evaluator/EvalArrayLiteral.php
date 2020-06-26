<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Types\ArrayLiteral;
use Monkey\Object\ArrayObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\InternalObject;

final class EvalArrayLiteral
{
    private Environment $env;
    private Evaluator $evaluator;

    public function __construct(
        Evaluator $evaluator,
        Environment $env
    ) {
        $this->env = $env;
        $this->evaluator = $evaluator;
    }

    public function __invoke(ArrayLiteral $node): InternalObject
    {
        $elements = $this->evaluator->evalExpressions($node->elements(), $this->env);

        if (1 === $elements && $elements[0] instanceof ErrorObject) {
            return $elements[0];
        }

        return new ArrayObject($elements);
    }
}
