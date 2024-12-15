<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Evaluator;

use MonkeyLang\Lang\Ast\Expressions\BinaryExpression;
use MonkeyLang\Lang\Ast\Expressions\CallExpression;
use MonkeyLang\Lang\Ast\Expressions\Expression;
use MonkeyLang\Lang\Ast\Expressions\IdentifierExpression;
use MonkeyLang\Lang\Ast\Expressions\IfExpression;
use MonkeyLang\Lang\Ast\Expressions\IndexExpression;
use MonkeyLang\Lang\Ast\Expressions\PostfixExpression;
use MonkeyLang\Lang\Ast\Expressions\UnaryExpression;
use MonkeyLang\Lang\Ast\Expressions\WhileExpression;
use MonkeyLang\Lang\Ast\Node;
use MonkeyLang\Lang\Ast\Program;
use MonkeyLang\Lang\Ast\Statements\AssignStatement;
use MonkeyLang\Lang\Ast\Statements\BlockStatement;
use MonkeyLang\Lang\Ast\Statements\ExpressionStatement;
use MonkeyLang\Lang\Ast\Statements\LetStatement;
use MonkeyLang\Lang\Ast\Statements\ReturnStatement;
use MonkeyLang\Lang\Ast\Types\ArrayLiteral;
use MonkeyLang\Lang\Ast\Types\BooleanLiteral;
use MonkeyLang\Lang\Ast\Types\FloatLiteral;
use MonkeyLang\Lang\Ast\Types\FunctionLiteral;
use MonkeyLang\Lang\Ast\Types\IntegerLiteral;
use MonkeyLang\Lang\Ast\Types\StringLiteral;
use MonkeyLang\Lang\Evaluator\Builtin\EvalFirstFunction;
use MonkeyLang\Lang\Evaluator\Builtin\EvalLastFunction;
use MonkeyLang\Lang\Evaluator\Builtin\EvalLenFunction;
use MonkeyLang\Lang\Evaluator\Builtin\EvalMapFunction;
use MonkeyLang\Lang\Evaluator\Builtin\EvalPushFunction;
use MonkeyLang\Lang\Evaluator\Builtin\EvalPutsFunction;
use MonkeyLang\Lang\Evaluator\Builtin\EvalSliceFunction;
use MonkeyLang\Lang\Object\BooleanObject;
use MonkeyLang\Lang\Object\ErrorObject;
use MonkeyLang\Lang\Object\FloatObject;
use MonkeyLang\Lang\Object\FunctionObject;
use MonkeyLang\Lang\Object\IntegerObject;
use MonkeyLang\Lang\Object\MonkeyObject;
use MonkeyLang\Lang\Object\NullObject;
use MonkeyLang\Lang\Object\StringObject;

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
