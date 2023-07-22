<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Expressions\BinaryExpression;
use Monkey\Ast\Expressions\CallExpression;
use Monkey\Ast\Expressions\Expression;
use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Ast\Expressions\IfExpression;
use Monkey\Ast\Expressions\IndexExpression;
use Monkey\Ast\Expressions\PostfixExpression;
use Monkey\Ast\Expressions\UnaryExpression;
use Monkey\Ast\Expressions\WhileExpression;
use Monkey\Ast\Node;
use Monkey\Ast\Program;
use Monkey\Ast\Statements\AssignStatement;
use Monkey\Ast\Statements\BlockStatement;
use Monkey\Ast\Statements\ExpressionStatement;
use Monkey\Ast\Statements\LetStatement;
use Monkey\Ast\Statements\ReturnStatement;
use Monkey\Ast\Types\ArrayLiteral;
use Monkey\Ast\Types\BooleanLiteral;
use Monkey\Ast\Types\FloatLiteral;
use Monkey\Ast\Types\FunctionLiteral;
use Monkey\Ast\Types\IntegerLiteral;
use Monkey\Ast\Types\StringLiteral;
use Monkey\Evaluator\Builtin\EvalFirstFunction;
use Monkey\Evaluator\Builtin\EvalLastFunction;
use Monkey\Evaluator\Builtin\EvalLenFunction;
use Monkey\Evaluator\Builtin\EvalMapFunction;
use Monkey\Evaluator\Builtin\EvalPushFunction;
use Monkey\Evaluator\Builtin\EvalPutsFunction;
use Monkey\Evaluator\Builtin\EvalSliceFunction;
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
    public array $builtinFunctions = [
        'map' => EvalMapFunction::class,
        'len' => EvalLenFunction::class,
        'last' => EvalLastFunction::class,
        'push' => EvalPushFunction::class,
        'first' => EvalFirstFunction::class,
        'slice' => EvalSliceFunction::class,
        'puts' => EvalPutsFunction::class,
    ];

    public function __construct()
    {
        $this->registerBuiltinFunctions();
    }

    public function eval(Node $node, Environment $environment): MonkeyObject
    {
        return match (true) {
            $node instanceof Program => (new EvalProgram($this, $environment))($node),
            $node instanceof IntegerLiteral => new IntegerObject($node->value),
            $node instanceof FloatLiteral => new FloatObject($node->value),
            $node instanceof StringLiteral => new StringObject($node->value),
            $node instanceof BooleanLiteral => BooleanObject::from($node->value),
            $node instanceof BlockStatement => (new EvalBlockStatement($this, $environment))($node),
            $node instanceof IfExpression => (new EvalIfExpression($this, $environment))($node),
            $node instanceof WhileExpression => (new EvalWhileExpression($this, $environment))($node),
            $node instanceof FunctionLiteral => new FunctionObject($node->parameters, $node->body, $environment),
            $node instanceof ExpressionStatement => $this->eval($node->expression, $environment),
            $node instanceof ReturnStatement => (new EvalReturnStatement($this, $environment))($node),
            $node instanceof CallExpression => (new EvalCallExpression($this, $environment))($node),
            $node instanceof ArrayLiteral => (new EvalArrayLiteral($this, $environment))($node),
            $node instanceof IndexExpression => (new EvalIndexExpression($this, $environment))($node),
            $node instanceof UnaryExpression => (new EvalUnaryExpression())(
                $node->operator,
                $this->eval($node->right, $environment)
            ),
            $node instanceof BinaryExpression => (new EvalBinaryExpression())(
                $node->operator,
                $this->eval($node->left, $environment),
                $this->eval($node->right, $environment)
            ),
            $node instanceof LetStatement => (new EvalLetStatement($this, $environment))($node),
            $node instanceof AssignStatement => (new EvalAssingStatement($this, $environment))($node),
            $node instanceof IdentifierExpression => (new EvalIdentifier($environment))($node),
            $node instanceof PostfixExpression => (new EvalPostfixExpression($environment))($node),
            default => NullObject::instance(),
        };
    }

    /**
     * @param array<Expression> $expressions
     *
     * @return array<MonkeyObject>
     */
    public function evalExpressions(array $expressions, Environment $environment): array
    {
        $result = [];

        /** @var Expression $expression */
        foreach ($expressions as $expression) {
            $object = $this->eval($expression, $environment);

            if ($object instanceof ErrorObject) {
                return [$object];
            }

            $result[] = $object;
        }

        return $result;
    }

    public function registerBuiltinFunctions(): void
    {
        foreach ($this->builtinFunctions as $funcName => $className) {
            BuiltinFunction::set($funcName, fn (MonkeyObject ...$monkeyObject) => (new $className($this))(...$monkeyObject));
        }
    }
}
