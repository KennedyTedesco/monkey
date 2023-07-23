<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Expressions\IfExpression;
use Monkey\Ast\Statements\BlockStatement;
use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\NullObject;

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
