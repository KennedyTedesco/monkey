<?php

declare(strict_types=1);

namespace Monkey\Evaluator\Builtin;

use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Evaluator\Evaluator;
use Monkey\Object\ArrayObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\FunctionObject;
use Monkey\Object\MonkeyObject;

final class EvalMapFunction
{
    public function __construct(private Evaluator $evaluator)
    {
    }

    public function __invoke(MonkeyObject ...$monkeyObject): MonkeyObject
    {
        if (2 !== \count($monkeyObject)) {
            return ErrorObject::wrongNumberOfArguments(\count($monkeyObject), 2);
        }

        $array = $monkeyObject[0];
        if (!$array instanceof ArrayObject) {
            return ErrorObject::invalidArgument('map()', $array->typeLiteral());
        }

        $callback = $monkeyObject[1];
        if (!$callback instanceof FunctionObject) {
            return ErrorObject::invalidArgument('map()', $callback->typeLiteral());
        }

        if (1 !== \count($callback->parameters())) {
            return ErrorObject::error('the callback of map accepts one parameter only.');
        }

        $environment = clone $callback->environment();

        /** @var IdentifierExpression $identifierExpression */
        $identifierExpression = $callback->parameter(0);

        $elements = [];
        foreach ($array->value() as $value) {
            $environment->set($identifierExpression->value(), $value);

            $elements[] = $this->evaluator->eval($callback->body(), $environment);
        }

        return new ArrayObject($elements);
    }
}
