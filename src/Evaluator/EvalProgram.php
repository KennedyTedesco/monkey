<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Program;
use Monkey\Object\ErrorObject;
use Monkey\Object\InternalObject;
use Monkey\Object\NullObject;
use Monkey\Object\ReturnValueObject;

final class EvalProgram
{
    private Evaluator $evaluator;

    public function __construct(Evaluator $evaluator)
    {
        $this->evaluator = $evaluator;
    }

    public function __invoke(Program $node): InternalObject
    {
        $result = NullObject::instance();

        foreach ($node->statements() as $statement) {
            $result = $this->evaluator->eval($statement);

            switch (true) {
                case $result instanceof ReturnValueObject:
                    return $result->value();
                case $result instanceof ErrorObject:
                    return $result;
            }
        }

        return $result;
    }
}
