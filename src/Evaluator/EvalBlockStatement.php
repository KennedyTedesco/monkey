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
    public function __construct(
        private Evaluator $evaluator,
        private Environment $environment
    ) {
    }

    public function __invoke(BlockStatement $blockStatement): MonkeyObject
    {
        $result = NullObject::instance();

        foreach ($blockStatement->statements() as $statement) {
            $result = $this->evaluator->eval($statement, $this->environment);
            if (true == $result instanceof ErrorObject) {
                return $result;
            }

            if (true == $result instanceof ReturnValueObject) {
                return $result;
            }
        }

        return $result;
    }
}
