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

final readonly class EvalCallExpression
{
    public function __construct(
        private Evaluator $evaluator,
        private Environment $environment,
    ) {
    }

    public function __invoke(CallExpression $callExpression): MonkeyObject
    {
        /** @var FunctionObject $monkeyObject */
        $monkeyObject = $this->evaluator->eval($callExpression->function(), $this->environment);

        if ($monkeyObject instanceof ErrorObject) {
            return $monkeyObject;
        }

        $args = $this->evaluator->evalExpressions($callExpression->arguments(), $this->environment);

        if ($args === 1 && $args[0] instanceof ErrorObject) {
            return $args[0];
        }

        return $this->applyFunction($monkeyObject, $args);
    }

    private function applyFunction(MonkeyObject $monkeyObject, array $args): MonkeyObject
    {
        if ($monkeyObject instanceof FunctionObject) {
            $environment = $this->extendFunctionEnv($monkeyObject, $args);

            return $this->unwrapReturnValue(
                $this->evaluator->eval($monkeyObject->body(), $environment),
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
            $environment->set($identifierExpression->value, $args[$index]);
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
