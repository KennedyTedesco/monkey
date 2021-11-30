<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Expressions\PostfixExpression;
use Monkey\Object\ErrorObject;
use Monkey\Object\IntegerObject;
use Monkey\Object\MonkeyObject;

final class EvalPostfixExpression
{
    public function __construct(private Environment $environment)
    {
    }

    public function __invoke(PostfixExpression $postfixExpression): MonkeyObject
    {
        $identifier = $postfixExpression->tokenLiteral();

        $object = $this->environment->get($identifier);
        if (!$object instanceof MonkeyObject) {
            return ErrorObject::identifierNotFound($identifier);
        }

        if (!$object instanceof IntegerObject) {
            return ErrorObject::error("postfix operator {$postfixExpression->operator()} only valid with integers.");
        }

        switch ($postfixExpression->operator()) {
            case '++':
                $this->environment->set(
                    $identifier, $newObject = new IntegerObject($object->value() + 1)
                );

                return $newObject;

            case '--':
                $this->environment->set(
                    $identifier, $newObject = new IntegerObject($object->value() - 1)
                );

                return $newObject;

            default:
                return ErrorObject::unknownOperator($postfixExpression->operator());
        }
    }
}
