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
        private Environment $env
    ) {
    }

    public function __invoke(CallExpression $node): MonkeyObject
    {
        /** @var FunctionObject $function */
        $function = $this->evaluator->eval($node->function(), $this->env);

        if ($function instanceof ErrorObject) {
            return $function;
        }

        $args = $this->evaluator->evalExpressions($node->arguments(), $this->env);

        if (1 === $args && $args[0] instanceof ErrorObject) {
            return $args[0];
        }

        return $this->applyFunction($function, $args);
    }

    private function applyFunction(MonkeyObject $function, array $args): MonkeyObject
    {
        if ($function instanceof FunctionObject) {
            $extendedEnv = $this->extendFunctionEnv($function, $args);

            return $this->unwrapReturnValue(
                $this->evaluator->eval($function->body(), $extendedEnv)
            );
        }

        if ($function instanceof BuiltinFunctionObject) {
            return $function->value()(...$args);
        }

        return ErrorObject::notAFunction($function->typeLiteral());
    }

    /**
     * @param array<MonkeyObject> $args
     */
    private function extendFunctionEnv(FunctionObject $function, array $args): Environment
    {
        $env = new Environment($function->environment());

        /** @var IdentifierExpression $parameter */
        foreach ($function->parameters() as $index => $parameter) {
            $env->set($parameter->value(), $args[$index]);
        }

        return $env;
    }

    private function unwrapReturnValue(MonkeyObject $object): MonkeyObject
    {
        if ($object instanceof ReturnValueObject) {
            return $object->value();
        }

        return $object;
    }
}
