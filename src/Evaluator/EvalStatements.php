<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Program;
use Monkey\Object\InternalObject;

final class EvalStatements
{
    private Evaluator $evaluator;

    public function __construct(Evaluator $evaluator)
    {
        $this->evaluator = $evaluator;
    }

    public function __invoke(Program $program): ?InternalObject
    {
        $result = null;
        foreach ($program->statements() as $statement) {
            $result = $this->evaluator->eval($statement);
        }

        return $result;
    }
}
