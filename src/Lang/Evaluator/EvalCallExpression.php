<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Evaluator;

use MonkeyLang\Lang\Ast\Expressions\CallExpression;
use MonkeyLang\Lang\Object\BuiltinFunctionObject;
use MonkeyLang\Lang\Object\ErrorObject;
use MonkeyLang\Lang\Object\FunctionObject;
use MonkeyLang\Lang\Object\MonkeyObject;
use MonkeyLang\Lang\Object\ReturnValueObject;

use function call_user_func;
use function count;

final readonly class EvalCallExpression
{
    public function __construct(
        public Evaluator $evaluator,
        public Environment $environment,
    ) {
    }

    public function __invoke(CallExpression $callExpression): MonkeyObject
    {
        /** @var ErrorObject|FunctionObject $monkeyObject */
        $monkeyObject = $this->evaluator->eval($callExpression->function, $this->environment);

        if ($monkeyObject instanceof ErrorObject) {
            return $monkeyObject;
        }

        $args = $this->evaluator->evalExpressions($callExpression->arguments, $this->environment);

        if (count($args) === 1 && $args[0] instanceof ErrorObject) {
            return $args[0];
        }

        return $this->applyFunction($monkeyObject, $args);
    }

    /**
     * @param array<MonkeyObject> $args
     */
    public function applyFunction(
        MonkeyObject $monkeyObject,
        array $args,
    ): MonkeyObject {
        if ($monkeyObject instanceof FunctionObject) {
            $environment = $this->extendFunctionEnv($monkeyObject, $args);

            return $this->unwrapReturnValue(
                $this->evaluator->eval($monkeyObject->body, $environment),
            );
        }

        if ($monkeyObject instanceof BuiltinFunctionObject) {
            /** @phpstan-ignore-next-line */
            return call_user_func($monkeyObject->value, ...$args);
        }

        return ErrorObject::notAFunction($monkeyObject->typeLiteral());
    }

    /**
     * @param array<MonkeyObject> $args
     */
    public function extendFunctionEnv(FunctionObject $functionObject, array $args): Environment
    {
        $environment = new Environment($functionObject->environment);

        foreach ($functionObject->parameters as $index => $identifierExpression) {
            $environment->set($identifierExpression->value, $args[$index]);
        }

        return $environment;
    }

    public function unwrapReturnValue(MonkeyObject $monkeyObject): MonkeyObject
    {
        if ($monkeyObject instanceof ReturnValueObject) {
            return $monkeyObject->value;
        }

        return $monkeyObject;
    }
}
