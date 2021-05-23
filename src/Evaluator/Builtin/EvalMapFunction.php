<?php

declare(strict_types=1);

namespace Monkey\Evaluator\Builtin;

use function count;
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

    public function __invoke(MonkeyObject ...$arguments): MonkeyObject
    {
        if (2 !== count($arguments)) {
            return ErrorObject::wrongNumberOfArguments(count($arguments), 2);
        }

        $array = $arguments[0];
        if (!$array instanceof ArrayObject) {
            return ErrorObject::invalidArgument('map()', $array->typeLiteral());
        }

        $callback = $arguments[1];
        if (!$callback instanceof FunctionObject) {
            return ErrorObject::invalidArgument('map()', $callback->typeLiteral());
        }

        if (1 !== count($callback->parameters())) {
            return ErrorObject::error('the callback of map accepts one parameter only.');
        }

        $environment = clone $callback->environment();

        /** @var IdentifierExpression $identifier */
        $identifier = $callback->parameter(0);

        $elements = [];
        foreach ($array->value() as $value) {
            $environment->set($identifier->value(), $value);

            $elements[] = $this->evaluator->eval($callback->body(), $environment);
        }

        return new ArrayObject($elements);
    }
}
