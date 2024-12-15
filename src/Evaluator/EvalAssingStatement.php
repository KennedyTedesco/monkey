<?php

declare(strict_types=1);

namespace MonkeyLang\Evaluator;

use MonkeyLang\Ast\Statements\AssignStatement;
use MonkeyLang\Object\ErrorObject;
use MonkeyLang\Object\MonkeyObject;

final readonly class EvalAssingStatement
{
    public function __construct(public Evaluator $evaluator, public Environment $environment)
    {
    }

    public function __invoke(AssignStatement $assignStatement): MonkeyObject
    {
        $monkeyObject = $this->evaluator->eval($assignStatement->value, $this->environment);

        if ($monkeyObject instanceof ErrorObject) {
            return $monkeyObject;
        }

        $name = $assignStatement->name->value;
        $nameMonkeyObject = $this->environment->get($name);

        if (!$nameMonkeyObject instanceof MonkeyObject) {
            return ErrorObject::identifierNotFound($name);
        }

        $this->environment->set($name, $monkeyObject);

        return $monkeyObject;
    }
}
