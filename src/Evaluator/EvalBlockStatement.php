<?php

declare(strict_types=1);

namespace MonkeyLang\Evaluator;

use MonkeyLang\Ast\Statements\BlockStatement;
use MonkeyLang\Object\ErrorObject;
use MonkeyLang\Object\MonkeyObject;
use MonkeyLang\Object\NullObject;
use MonkeyLang\Object\ReturnValueObject;

final readonly class EvalBlockStatement
{
    public function __construct(
        public Evaluator $evaluator,
        public Environment $environment,
    ) {
    }

    public function __invoke(BlockStatement $blockStatement): MonkeyObject
    {
        $result = NullObject::instance();

        foreach ($blockStatement->statements as $statement) {
            $result = $this->evaluator->eval($statement, $this->environment);

            if ($result instanceof ErrorObject) {
                return $result;
            }

            if ($result instanceof ReturnValueObject) {
                return $result;
            }
        }

        return $result;
    }
}
