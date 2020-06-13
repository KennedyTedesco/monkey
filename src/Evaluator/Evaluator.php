<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Expressions\UnaryExpression;
use Monkey\Ast\Node;
use Monkey\Ast\Program;
use Monkey\Ast\Statements\ExpressionStatement;
use Monkey\Ast\Types\BooleanLiteral;
use Monkey\Ast\Types\IntegerLiteral;
use Monkey\Object\BooleanObject;
use Monkey\Object\IntegerObject;
use Monkey\Object\InternalObject;
use Monkey\Object\NullObject;

final class Evaluator
{
    public function eval(Node $node): ?InternalObject
    {
        if ($node instanceof Program) {
            return $this->evalStatements($node);
        }

        if ($node instanceof ExpressionStatement) {
            return $this->eval($node->expression());
        }

        if ($node instanceof IntegerLiteral) {
            return new IntegerObject($node->value());
        }

        if ($node instanceof BooleanLiteral) {
            return true === $node->value() ? BooleanObject::true() : BooleanObject::false();
        }

        if ($node instanceof UnaryExpression) {
            return $this->evalUnaryExpression($node->operator(), $this->eval($node->right()));
        }

        return null;
    }

    private function evalStatements(Program $program): ?InternalObject
    {
        $result = null;
        foreach ($program->statements() as $statement) {
            $result = $this->eval($statement);
        }

        return $result;
    }

    private function evalUnaryExpression(string $operator, InternalObject $right): ?InternalObject
    {
        switch ($operator) {
            case '!':
                return $this->evalBangOperatorExpression($right);
            default:
                return null;
        }
    }

    private function evalBangOperatorExpression(InternalObject $right): InternalObject
    {
        if ($right instanceof BooleanObject) {
            return $right->value() ? BooleanObject::false() : BooleanObject::true();
        }

        if ($right instanceof NullObject) {
            return BooleanObject::true();
        }

        return BooleanObject::false();
    }
}
