<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Expressions\BinaryExpression;
use Monkey\Ast\Expressions\CallExpression;
use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Ast\Expressions\IfExpression;
use Monkey\Ast\Expressions\UnaryExpression;
use Monkey\Ast\Node;
use Monkey\Ast\Program;
use Monkey\Ast\Statements\BlockStatement;
use Monkey\Ast\Statements\ExpressionStatement;
use Monkey\Ast\Statements\LetStatement;
use Monkey\Ast\Statements\ReturnStatement;
use Monkey\Ast\Types\BooleanLiteral;
use Monkey\Ast\Types\FunctionLiteral;
use Monkey\Ast\Types\IntegerLiteral;
use Monkey\Ast\Types\StringLiteral;
use Monkey\Evaluator\Builtin\EvalLenFunction;
use Monkey\Object\BooleanObject;
use Monkey\Object\BuiltinFunctionObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\FunctionObject;
use Monkey\Object\IntegerObject;
use Monkey\Object\InternalObject;
use Monkey\Object\NullObject;
use Monkey\Object\ReturnValueObject;
use Monkey\Object\StringObject;

final class Evaluator
{
    public function __construct()
    {
        BuiltinFunction::set('len', function (InternalObject ...$arguments) {
            return (new EvalLenFunction())(...$arguments);
        });
    }

    public function eval(Node $node, Environment $env): InternalObject
    {
        switch (true) {
            case $node instanceof Program:
                return (new EvalProgram($this, $env))($node);

            case $node instanceof BlockStatement:
                return (new EvalBlockStatement($this, $env))($node);

            case $node instanceof IfExpression:
                return (new EvalIfExpression($this, $env))($node);

            case $node instanceof FunctionLiteral:
                return new FunctionObject($node->parameters(), $node->body(), $env);

            case $node instanceof ExpressionStatement:
                return $this->eval($node->expression(), $env);

            case $node instanceof IntegerLiteral:
                return new IntegerObject($node->value());

            case $node instanceof StringLiteral:
                return new StringObject($node->value());

            case $node instanceof BooleanLiteral:
                return BooleanObject::from($node->value());

            case $node instanceof ReturnStatement:
                if ($this->isError($object = $this->eval($node->returnValue(), $env))) {
                    return $object;
                }
                return new ReturnValueObject($object);

            case $node instanceof CallExpression:
                /** @var FunctionObject $function */
                $function = $this->eval($node->function(), $env);
                if ($this->isError($function)) {
                    return $function;
                }
                $args = $this->evalExpressions($node->arguments(), $env);
                if (1 === $args && $this->isError($args[0])) {
                    return $args[0];
                }
                return $this->applyFunction($function, $args);

            case $node instanceof UnaryExpression:
                return (new EvalUnaryExpression())(
                    $node->operator(),
                    $this->eval($node->right(), $env)
                );

            case $node instanceof BinaryExpression:
                return (new EvalBinaryExpression())(
                    $node->operator(),
                    $this->eval($node->left(), $env),
                    $this->eval($node->right(), $env)
                );

            case $node instanceof LetStatement:
                return (new EvalLetStatement($this, $env))($node);

            case $node instanceof IdentifierExpression:
                return (new EvalIdentifier($env))($node);

            default:
                return NullObject::instance();
        }
    }

    /**
     * @param array<Expression> $expressions
     *
     * @return array<InternalObject>
     */
    public function evalExpressions(array $expressions, Environment $env): array
    {
        $result = [];
        /** @var Expression $expression */
        foreach ($expressions as $expression) {
            $object = $this->eval($expression, $env);
            if ($this->isError($object)) {
                return [$object];
            }
            $result[] = $object;
        }

        return $result;
    }

    private function applyFunction(InternalObject $function, array $args): InternalObject
    {
        if ($function instanceof FunctionObject) {
            $extendedEnv = $this->extendFunctionEnv($function, $args);

            return $this->unwrapReturnValue(
                $this->eval($function->body(), $extendedEnv)
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

    private function isError(InternalObject $object): bool
    {
        return $object instanceof ErrorObject;
    }
}
