<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Expressions\CallExpression;
use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Object\BuiltinFunctionObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\FunctionObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\ReturnValueObject;

final class EvalCallExpression
{
    public function __construct(
        private Evaluator $evaluator,
        private Environment $environment
    ) {
    }

    public function __invoke(CallExpression $callExpression): MonkeyObject
    {
        /** @var FunctionObject $function */
        $function = $this->evaluator->eval($callExpression->function(), $this->environment);

        if ($function instanceof ErrorObject) {
            return $function;
        }

        $args = $this->evaluator->evalExpressions($callExpression->arguments(), $this->environment);

        if (1 === $args && $args[0] instanceof ErrorObject) {
            return $args[0];
        }

        return $this->applyFunction($function, $args);
    }

    private function applyFunction(MonkeyObject $monkeyObject, array $args): MonkeyObject
    {
        if ($monkeyObject instanceof FunctionObject) {
            $environment = $this->extendFunctionEnv($monkeyObject, $args);

            return $this->unwrapReturnValue(
                $this->evaluator->eval($monkeyObject->body(), $environment)
            );
        }

        if ($monkeyObject instanceof BuiltinFunctionObject) {
            return $monkeyObject->value()(...$args);
        }

        return ErrorObject::notAFunction($monkeyObject->typeLiteral());
    }

    /**
     * @param array<MonkeyObject> $args
     */
    private function extendFunctionEnv(FunctionObject $functionObject, array $args): Environment
    {
        $environment = new Environment($functionObject->environment());

        /** @var IdentifierExpression $identifierExpression */
        foreach ($functionObject->parameters() as $index => $identifierExpression) {
            $environment->set($identifierExpression->value(), $args[$index]);
        }

        return $environment;
    }

    private function unwrapReturnValue(MonkeyObject $monkeyObject): MonkeyObject
    {
        if ($monkeyObject instanceof ReturnValueObject) {
            return $monkeyObject->value();
        }

        return $monkeyObject;
    }
}
