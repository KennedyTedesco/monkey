<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Evaluator;

use MonkeyLang\Lang\Ast\Program;
use MonkeyLang\Lang\Object\ErrorObject;
use MonkeyLang\Lang\Object\MonkeyObject;
use MonkeyLang\Lang\Object\NullObject;
use MonkeyLang\Lang\Object\ReturnValueObject;

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
