<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Expressions\BinaryExpression;
use Monkey\Ast\Expressions\CallExpression;
use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Ast\Expressions\IfExpression;
use Monkey\Ast\Expressions\IndexExpression;
use Monkey\Ast\Expressions\UnaryExpression;
use Monkey\Ast\Expressions\WhileExpression;
use Monkey\Ast\Node;
use Monkey\Ast\Program;
use Monkey\Ast\Statements\AssignStatement;
use Monkey\Ast\Statements\BlockStatement;
use Monkey\Ast\Statements\ExpressionStatement;
use Monkey\Ast\Statements\LetStatement;
use Monkey\Ast\Statements\PrintStatement;
use Monkey\Ast\Statements\ReturnStatement;
use Monkey\Ast\Types\ArrayLiteral;
use Monkey\Ast\Types\BooleanLiteral;
use Monkey\Ast\Types\FloatLiteral;
use Monkey\Ast\Types\FunctionLiteral;
use Monkey\Ast\Types\IntegerLiteral;
use Monkey\Ast\Types\StringLiteral;
use Monkey\Evaluator\Builtin\EvalLenFunction;
use Monkey\Object\BooleanObject;
use Monkey\Object\ErrorObject;
use Monkey\Object\FloatObject;
use Monkey\Object\FunctionObject;
use Monkey\Object\IntegerObject;
use Monkey\Object\MonkeyObject;
use Monkey\Object\NullObject;
use Monkey\Object\StringObject;

final class Evaluator
{
    public function __construct()
    {
        BuiltinFunction::set('len', function (MonkeyObject ...$arguments) {
            return (new EvalLenFunction())(...$arguments);
        });
    }

    public function eval(Node $node, Environment $env): MonkeyObject
    {
        switch (true) {
            case $node instanceof Program:
                return (new EvalProgram($this, $env))($node);

            case $node instanceof IntegerLiteral:
                return new IntegerObject($node->value());

            case $node instanceof FloatLiteral:
                return new FloatObject($node->value());

            case $node instanceof StringLiteral:
                return new StringObject($node->value());

            case $node instanceof BooleanLiteral:
                return BooleanObject::from($node->value());

            case $node instanceof BlockStatement:
                return (new EvalBlockStatement($this, $env))($node);

            case $node instanceof IfExpression:
                return (new EvalIfExpression($this, $env))($node);

            case $node instanceof WhileExpression:
                return (new EvalWhileExpression($this, $env))($node);

            case $node instanceof FunctionLiteral:
                return new FunctionObject($node->parameters(), $node->body(), $env);

            case $node instanceof ExpressionStatement:
                return $this->eval($node->expression(), $env);

            case $node instanceof ReturnStatement:
                return (new EvalReturnStatement($this, $env))($node);

            case $node instanceof CallExpression:
                return (new EvalCallExpression($this, $env))($node);

            case $node instanceof ArrayLiteral:
                return (new EvalArrayLiteral($this, $env))($node);

            case $node instanceof IndexExpression:
                return (new EvalIndexExpression($this, $env))($node);

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

            case $node instanceof AssignStatement:
                return (new EvalAssingStatement($this, $env))($node);

            case $node instanceof PrintStatement:
                return (new EvalPrintStatement($this, $env))($node);

            case $node instanceof IdentifierExpression:
                return (new EvalIdentifier($env))($node);

            default:
                return NullObject::instance();
        }
    }

    /**
     * @param array<Expression> $expressions
     *
     * @return array<MonkeyObject>
     */
    public function evalExpressions(array $expressions, Environment $env): array
    {
        $result = [];

        /** @var Expression $expression */
        foreach ($expressions as $expression) {
            $object = $this->eval($expression, $env);

            if ($object instanceof ErrorObject) {
                return [$object];
            }

            $result[] = $object;
        }

        return $result;
    }
}
