<?php

declare(strict_types=1);

namespace MonkeyLang\Evaluator;

use MonkeyLang\Ast\Types\ArrayLiteral;
use MonkeyLang\Object\ArrayObject;
use MonkeyLang\Object\ErrorObject;
use MonkeyLang\Object\MonkeyObject;

use function count;

final readonly class EvalArrayLiteral
{
    public function __construct(
        public Evaluator $evaluator,
        public Environment $environment,
    ) {
    }

    public function __invoke(ArrayLiteral $arrayLiteral): MonkeyObject
    {
        $elements = $this->evaluator->evalExpressions($arrayLiteral->elements, $this->environment);

        if (count($elements) === 1 && $elements[0] instanceof ErrorObject) {
            return $elements[0];
        }

        return new ArrayObject($elements);
    }
}
