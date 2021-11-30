<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Statements\LetStatement;
use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;

final class EvalLetStatement
{
    public function __construct(private Evaluator $evaluator, private Environment $environment)
    {
    }

    public function __invoke(LetStatement $letStatement): MonkeyObject
    {
        $value = $this->evaluator->eval($letStatement->value(), $this->environment);

        if ($value instanceof ErrorObject) {
            return $value;
        }

        $this->environment->set($letStatement->name()->value(), $value);

        return $this->evaluator->eval($letStatement->name(), $this->environment);
    }
}
