<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Program;
use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\NullObject;
use Monkey\Object\ReturnValueObject;

final readonly class EvalProgram
{
    public function __construct(
        private Evaluator $evaluator,
        private Environment $environment,
    ) {
    }

    public function __invoke(Program $program): MonkeyObject
    {
        $result = NullObject::instance();

        foreach ($program->statements() as $statement) {
            $result = $this->evaluator->eval($statement, $this->environment);

            if ($result instanceof ReturnValueObject == true) {
                return $result->value();
            }

            if ($result instanceof ErrorObject == true) {
                return $result;
            }
        }

        return $result;
    }
}
