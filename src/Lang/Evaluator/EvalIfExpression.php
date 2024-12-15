<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Evaluator;

use MonkeyLang\Lang\Ast\Expressions\IfExpression;
use MonkeyLang\Lang\Ast\Statements\BlockStatement;
use MonkeyLang\Lang\Object\ErrorObject;
use MonkeyLang\Lang\Object\MonkeyObject;
use MonkeyLang\Lang\Object\NullObject;

final readonly class EvalIfExpression
{
    public function __construct(
        public Evaluator $evaluator,
        public Environment $environment,
    ) {
    }

    public function __invoke(IfExpression $ifExpression): MonkeyObject
    {
        $monkeyObject = $this->evaluator->eval($ifExpression->condition, $this->environment);

        return match (true) {
            $monkeyObject instanceof ErrorObject => $monkeyObject,

            (bool)$monkeyObject->value() =>
                $this->evaluator->eval($ifExpression->consequence, $this->environment),

            $ifExpression->alternative instanceof BlockStatement =>
                $this->evaluator->eval($ifExpression->alternative, $this->environment),

            default => NullObject::instance(),
        };
    }
}
