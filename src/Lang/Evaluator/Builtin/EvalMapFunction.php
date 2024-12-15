<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Evaluator\Builtin;

use MonkeyLang\Lang\Ast\Expressions\IdentifierExpression;
use MonkeyLang\Lang\Object\ArrayObject;
use MonkeyLang\Lang\Object\ErrorObject;
use MonkeyLang\Lang\Object\FunctionObject;
use MonkeyLang\Lang\Object\MonkeyObject;

use function count;

final readonly class EvalMapFunction extends EvalBuiltinFunction
{
    public function __invoke(MonkeyObject ...$monkeyObject): MonkeyObject
    {
        if (count($monkeyObject) !== 2) {
            return ErrorObject::wrongNumberOfArguments(count($monkeyObject), 2);
        }

        $array = $monkeyObject[0];

        if (!$array instanceof ArrayObject) {
            return ErrorObject::invalidArgument('map()', $array->typeLiteral());
        }

        $callback = $monkeyObject[1];

        if (!$callback instanceof FunctionObject) {
            return ErrorObject::invalidArgument('map()', $callback->typeLiteral());
        }

        if (count($callback->parameters) !== 1) {
            return ErrorObject::error('the callback of map accepts one parameter only.');
        }

        $environment = clone $callback->environment;

        /** @var IdentifierExpression $identifierExpression */
        $identifierExpression = $callback->parameter(0);

        $elements = [];

        foreach ($array->value as $value) {
            $environment->set($identifierExpression->value, $value);

            $elements[] = $this->evaluator->eval($callback->body, $environment);
        }

        return new ArrayObject($elements);
    }
}
