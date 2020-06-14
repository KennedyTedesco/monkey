<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\HasStatements;
use Monkey\Ast\Node;
use Monkey\Ast\Statements\Statement;
use Monkey\Object\InternalObject;
use Monkey\Object\NullObject;

final class EvalStatements
{
    private Evaluator $evaluator;

    public function __construct(Evaluator $evaluator)
    {
        $this->evaluator = $evaluator;
    }

    public function __invoke(Node $node): InternalObject
    {
        $result = NullObject::instance();

        if ($node instanceof HasStatements) {
            /** @var Statement $statement */
            foreach ($node->statements() as $statement) {
                $result = $this->evaluator->eval($statement);
            }
        }

        return $result;
    }
}
