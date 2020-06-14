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
use Monkey\Ast\Types\BooleanLiteral;
use Monkey\Ast\Types\IntegerLiteral;
use Monkey\Object\BooleanObject;
use Monkey\Object\IntegerObject;
use Monkey\Object\InternalObject;
use Monkey\Object\NullObject;

final class Evaluator
{
    public function eval(Node $node): InternalObject
    {
        if ($node instanceof Program || $node instanceof BlockStatement) {
            return (new EvalStatements($this))($node);
        }

        if ($node instanceof IfExpression) {
            return (new EvalIfExpression($this))($node);
        }

        if ($node instanceof ExpressionStatement) {
            return $this->eval($node->expression());
        }

        if ($node instanceof IntegerLiteral) {
            return new IntegerObject($node->value());
        }

        if ($node instanceof BooleanLiteral) {
            return BooleanObject::from($node->value());
        }

        if ($node instanceof UnaryExpression) {
            return (new EvalUnaryExpression())(
                $node->operator(),
                $this->eval($node->right())
            );
        }

        if ($node instanceof BinaryExpression) {
            return (new EvalBinaryExpression())(
                $node->operator(),
                $this->eval($node->left()),
                $this->eval($node->right())
            );
        }

        return NullObject::instance();
    }
}
