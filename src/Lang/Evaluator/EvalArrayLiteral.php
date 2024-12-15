<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Evaluator;

use MonkeyLang\Lang\Ast\Types\ArrayLiteral;
use MonkeyLang\Lang\Object\ArrayObject;
use MonkeyLang\Lang\Object\ErrorObject;
use MonkeyLang\Lang\Object\MonkeyObject;

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
