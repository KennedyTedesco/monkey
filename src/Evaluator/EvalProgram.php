<?php

declare(strict_types=1);

namespace MonkeyLang\Evaluator;

use MonkeyLang\Ast\Program;
use MonkeyLang\Object\ErrorObject;
use MonkeyLang\Object\MonkeyObject;
use MonkeyLang\Object\NullObject;
use MonkeyLang\Object\ReturnValueObject;

final readonly class EvalProgram
{
    public function __construct(
        public Evaluator $evaluator,
        public Environment $environment,
    ) {
    }

    public function __invoke(Program $program): MonkeyObject
    {
        $result = NullObject::instance();

        foreach ($program->statements() as $statement) {
            $result = $this->evaluator->eval($statement, $this->environment);

            if ($result instanceof ReturnValueObject) {
                return $result->value;
            }

            if ($result instanceof ErrorObject) {
                return $result;
            }
        }

        return $result;
    }
}
