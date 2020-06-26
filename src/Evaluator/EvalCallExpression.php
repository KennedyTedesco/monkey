<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Expressions\CallExpression;
use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Object\BuiltinFunctionObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\FunctionObject;
use Monkey\Object\InternalObject;
use Monkey\Object\ReturnValueObject;

final class EvalCallExpression
{
    private Environment $env;
    private Evaluator $evaluator;

    public function __construct(
        Evaluator $evaluator,
        Environment $env
    ) {
        $this->env = $env;
        $this->evaluator = $evaluator;
    }

    public function __invoke(CallExpression $node): InternalObject
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

    private function applyFunction(InternalObject $function, array $args): InternalObject
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

        return ErrorObject::notAFunction($function->type());
    }

    /**
     * @param array<InternalObject> $args
     */
    private function extendFunctionEnv(FunctionObject $function, array $args): Environment
    {
        $env = Environment::newEnclosed($function->environment());
        /** @var IdentifierExpression $parameter */
        foreach ($function->parameters() as $index => $parameter) {
            $env->set($parameter->value(), $args[$index]);
        }

        return $env;
    }

    private function unwrapReturnValue(InternalObject $object): InternalObject
    {
        if ($object instanceof ReturnValueObject) {
            return $object->value();
        }

        return $object;
    }
}
