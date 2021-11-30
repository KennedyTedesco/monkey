<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Statements\AssignStatement;
use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;

final class EvalAssingStatement
{
    public function __construct(private Evaluator $evaluator, private Environment $environment)
    {
    }

    public function __invoke(AssignStatement $assignStatement): MonkeyObject
    {
        $value = $this->evaluator->eval($assignStatement->value(), $this->environment);

        if ($value instanceof ErrorObject) {
            return $value;
        }

        $name = $assignStatement->name()->value();
        $nameMonkeyObject = $this->environment->get($name);

        if (!$nameMonkeyObject instanceof MonkeyObject) {
            return ErrorObject::identifierNotFound($name);
        }

        $this->environment->set($name, $value);

        return $value;
    }
}
