<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Expressions\PostfixExpression;
use Monkey\Object\ErrorObject;
use Monkey\Object\IntegerObject;
use Monkey\Object\MonkeyObject;

final class EvalPostfixExpression
{
    private Environment $env;

    public function __construct(Environment $env)
    {
        $this->env = $env;
    }

    public function __invoke(PostfixExpression $node): MonkeyObject
    {
        $identifier = $node->tokenLiteral();

        $object = $this->env->get($identifier);
        if (null === $object) {
            return ErrorObject::identifierNotFound($identifier);
        }

        if (!$object instanceof IntegerObject) {
            return ErrorObject::error("postfix operator {$node->operator()} only valid with integers.");
        }

        switch ($node->operator()) {
            case '++':
                $this->env->set(
                    $identifier, $newObject = new IntegerObject($object->value() + 1)
                );
                return $newObject;

            case '--':
                $this->env->set(
                    $identifier, $newObject = new IntegerObject($object->value() - 1)
                );
                return $newObject;

            default:
                return ErrorObject::unknownOperator($node->operator());
        }
    }
}
