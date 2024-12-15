<?php

declare(strict_types=1);

namespace MonkeyLang\Evaluator;

use MonkeyLang\Ast\Expressions\BinaryExpression;
use MonkeyLang\Ast\Expressions\CallExpression;
use MonkeyLang\Ast\Expressions\Expression;
use MonkeyLang\Ast\Expressions\IdentifierExpression;
use MonkeyLang\Ast\Expressions\IfExpression;
use MonkeyLang\Ast\Expressions\IndexExpression;
use MonkeyLang\Ast\Expressions\PostfixExpression;
use MonkeyLang\Ast\Expressions\UnaryExpression;
use MonkeyLang\Ast\Expressions\WhileExpression;
use MonkeyLang\Ast\Node;
use MonkeyLang\Ast\Program;
use MonkeyLang\Ast\Statements\AssignStatement;
use MonkeyLang\Ast\Statements\BlockStatement;
use MonkeyLang\Ast\Statements\ExpressionStatement;
use MonkeyLang\Ast\Statements\LetStatement;
use MonkeyLang\Ast\Statements\ReturnStatement;
use MonkeyLang\Ast\Types\ArrayLiteral;
use MonkeyLang\Ast\Types\BooleanLiteral;
use MonkeyLang\Ast\Types\FloatLiteral;
use MonkeyLang\Ast\Types\FunctionLiteral;
use MonkeyLang\Ast\Types\IntegerLiteral;
use MonkeyLang\Ast\Types\StringLiteral;
use MonkeyLang\Evaluator\Builtin\EvalFirstFunction;
use MonkeyLang\Evaluator\Builtin\EvalLastFunction;
use MonkeyLang\Evaluator\Builtin\EvalLenFunction;
use MonkeyLang\Evaluator\Builtin\EvalMapFunction;
use MonkeyLang\Evaluator\Builtin\EvalPushFunction;
use MonkeyLang\Evaluator\Builtin\EvalPutsFunction;
use MonkeyLang\Evaluator\Builtin\EvalSliceFunction;
use MonkeyLang\Object\BooleanObject;
use MonkeyLang\Object\ErrorObject;
use MonkeyLang\Object\FloatObject;
use MonkeyLang\Object\FunctionObject;
use MonkeyLang\Object\IntegerObject;
use MonkeyLang\Object\MonkeyObject;
use MonkeyLang\Object\NullObject;
use MonkeyLang\Object\StringObject;

use function call_user_func;

final class Evaluator
{
    public function __construct()
    {
        $this->registerBuiltinFunctions();
    }

    public function eval(Node $node, Environment $environment): MonkeyObject
    {
        return match (true) {
            $node instanceof Program => new EvalProgram($this, $environment)($node),
            $node instanceof IntegerLiteral => new IntegerObject($node->value),
            $node instanceof FloatLiteral => new FloatObject($node->value),
            $node instanceof StringLiteral => new StringObject($node->value),
            $node instanceof BooleanLiteral => BooleanObject::from($node->value),
            $node instanceof BlockStatement => new EvalBlockStatement($this, $environment)($node),
            $node instanceof IfExpression => new EvalIfExpression($this, $environment)($node),
            $node instanceof WhileExpression => new EvalWhileExpression($this, $environment)($node),
            $node instanceof FunctionLiteral => new FunctionObject($node->parameters, $node->body, $environment),
            $node instanceof ExpressionStatement => $this->eval($node->expression, $environment),
            $node instanceof ReturnStatement => new EvalReturnStatement($this, $environment)($node),
            $node instanceof CallExpression => new EvalCallExpression($this, $environment)($node),
            $node instanceof ArrayLiteral => new EvalArrayLiteral($this, $environment)($node),
            $node instanceof IndexExpression => new EvalIndexExpression($this, $environment)($node),
            $node instanceof UnaryExpression => new EvalUnaryExpression()(
                $node->operator,
                $this->eval($node->right, $environment)
            ),
            $node instanceof BinaryExpression => new EvalBinaryExpression()(
                $node->operator,
                $this->eval($node->left, $environment),
                $this->eval($node->right, $environment)
            ),
            $node instanceof LetStatement => new EvalLetStatement($this, $environment)($node),
            $node instanceof AssignStatement => new EvalAssingStatement($this, $environment)($node),
            $node instanceof IdentifierExpression => new EvalIdentifier($environment)($node),
            $node instanceof PostfixExpression => new EvalPostfixExpression($environment)($node),
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
        $builtinFunctions = [
            'map' => EvalMapFunction::class,
            'len' => EvalLenFunction::class,
            'last' => EvalLastFunction::class,
            'push' => EvalPushFunction::class,
            'first' => EvalFirstFunction::class,
            'slice' => EvalSliceFunction::class,
            'puts' => EvalPutsFunction::class,
        ];

        foreach ($builtinFunctions as $funcName => $evalClassName) {
            BuiltinFunction::set($funcName, fn (MonkeyObject ...$monkeyObject): MonkeyObject => call_user_func(new $evalClassName($this), ...$monkeyObject));
        }
    }
}
