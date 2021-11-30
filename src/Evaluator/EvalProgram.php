<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Program;
use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\NullObject;
use Monkey\Object\ReturnValueObject;

final class EvalProgram
{
    public function __construct(private Evaluator $evaluator, private Environment $env)
    {
    }

    public function __invoke(Program $node): MonkeyObject
    {
        $result = NullObject::instance();

        foreach ($node->statements() as $statement) {
            $result = $this->evaluator->eval($statement, $this->env);
            if (true == $result instanceof ReturnValueObject) {
                return $result->value();
            }

            if (true == $result instanceof ErrorObject) {
                return $result;
            }
        }

        return $result;
    }
}
