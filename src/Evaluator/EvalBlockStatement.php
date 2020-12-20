<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Statements\BlockStatement;
use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\NullObject;
use Monkey\Object\ReturnValueObject;

final class EvalBlockStatement
{
    public function __construct(private Evaluator $evaluator, private Environment $env)
    {
    }

    public function __invoke(BlockStatement $node): MonkeyObject
    {
        $result = NullObject::instance();

        foreach ($node->statements() as $statement) {
            $result = $this->evaluator->eval($statement, $this->env);

            switch (true) {
                case $result instanceof ErrorObject:
                case $result instanceof ReturnValueObject:
                    return $result;
            }
        }

        return $result;
    }
}
