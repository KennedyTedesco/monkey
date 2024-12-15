<?php

declare(strict_types=1);

namespace MonkeyLang\Evaluator;

use MonkeyLang\Ast\Expressions\IfExpression;
use MonkeyLang\Ast\Statements\BlockStatement;
use MonkeyLang\Object\ErrorObject;
use MonkeyLang\Object\MonkeyObject;
use MonkeyLang\Object\NullObject;

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
