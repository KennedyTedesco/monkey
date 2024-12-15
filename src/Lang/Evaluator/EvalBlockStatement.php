<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Evaluator;

use MonkeyLang\Lang\Ast\Statements\BlockStatement;
use MonkeyLang\Lang\Object\ErrorObject;
use MonkeyLang\Lang\Object\MonkeyObject;
use MonkeyLang\Lang\Object\NullObject;
use MonkeyLang\Lang\Object\ReturnValueObject;

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
