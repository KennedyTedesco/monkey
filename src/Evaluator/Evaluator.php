<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Expressions\BinaryExpression;
use Monkey\Ast\Expressions\IfExpression;
use Monkey\Ast\Expressions\UnaryExpression;
use Monkey\Ast\Node;
use Monkey\Ast\Program;
use Monkey\Ast\Statements\BlockStatement;
use Monkey\Ast\Statements\ExpressionStatement;
use Monkey\Ast\Statements\ReturnStatement;
use Monkey\Ast\Types\BooleanLiteral;
use Monkey\Ast\Types\IntegerLiteral;
use Monkey\Object\BooleanObject;
use Monkey\Object\IntegerObject;
use Monkey\Object\InternalObject;
use Monkey\Object\NullObject;
use Monkey\Object\ReturnValueObject;

final class Evaluator
{
    public function eval(Node $node): InternalObject
    {
        switch (true) {
            case $node instanceof Program:
                return (new EvalProgram($this))($node);

            case $node instanceof BlockStatement:
                return (new EvalBlockStatement($this))($node);

            case $node instanceof IfExpression:
                return (new EvalIfExpression($this))($node);

            case $node instanceof ExpressionStatement:
                return $this->eval($node->expression());

            case $node instanceof IntegerLiteral:
                return new IntegerObject($node->value());

            case $node instanceof BooleanLiteral:
                return BooleanObject::from($node->value());

            case $node instanceof ReturnStatement:
                return new ReturnValueObject($this->eval($node->returnValue()));

            case $node instanceof UnaryExpression:
                return (new EvalUnaryExpression())(
                    $node->operator(), $this->eval($node->right())
                );

            case $node instanceof BinaryExpression:
                return (new EvalBinaryExpression())(
                    $node->operator(), $this->eval($node->left()), $this->eval($node->right())
                );

            default:
                return NullObject::instance();
        }
    }
}
