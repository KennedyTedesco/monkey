<?php

declare(strict_types=1);

namespace MonkeyLang\Evaluator;

use MonkeyLang\Ast\Expressions\PostfixExpression;
use MonkeyLang\Object\ErrorObject;
use MonkeyLang\Object\IntegerObject;
use MonkeyLang\Object\MonkeyObject;

final readonly class EvalPostfixExpression
{
    public function __construct(
        public Environment $environment,
    ) {
    }

    public function __invoke(PostfixExpression $postfixExpression): MonkeyObject
    {
        $identifier = $postfixExpression->token->literal;

        $monkeyObject = $this->environment->get($identifier);

        if (!$monkeyObject instanceof MonkeyObject) {
            return ErrorObject::identifierNotFound($identifier);
        }

        if (!$monkeyObject instanceof IntegerObject) {
            return ErrorObject::error("postfix operator {$postfixExpression->operator} only valid with integers.");
        }

        switch ($postfixExpression->operator) {
            case '++':
                $this->environment->set(
                    $identifier,
                    $newObject = new IntegerObject($monkeyObject->value + 1),
                );

                return $newObject;
            case '--':
                $this->environment->set(
                    $identifier,
                    $newObject = new IntegerObject($monkeyObject->value - 1),
                );

                return $newObject;

            default:
                return ErrorObject::unknownOperator($postfixExpression->operator);
        }
    }
}
