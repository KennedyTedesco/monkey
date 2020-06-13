<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Node;
use Monkey\Ast\Program;
use Monkey\Ast\Statements\ExpressionStatement;
use Monkey\Ast\Types\BooleanLiteral;
use Monkey\Ast\Types\IntegerLiteral;
use Monkey\Object\BooleanObject;
use Monkey\Object\IntegerObject;
use Monkey\Object\InternalObject;

final class Evaluator
{
    public function eval(Node $node): ?InternalObject
    {
        if ($node instanceof Program) {
            return $this->statements($node);
        }

        if ($node instanceof ExpressionStatement) {
            return $this->eval($node->expression());
        }

        if ($node instanceof IntegerLiteral) {
            return new IntegerObject($node->value());
        }

        if ($node instanceof BooleanLiteral) {
            return new BooleanObject($node->value());
        }

        return null;
    }

    private function statements(Program $program): ?InternalObject
    {
        $result = null;
        foreach ($program->statements() as $statement) {
            $result = $this->eval($statement);
        }

        return $result;
    }
}
