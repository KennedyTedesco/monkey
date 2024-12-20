<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Parser;

use MonkeyLang\Lang\Ast\Expressions\Expression;
use MonkeyLang\Lang\Lexer\Lexer;
use MonkeyLang\Lang\Parser\Parselet\ArrayParselet;
use MonkeyLang\Lang\Parser\Parselet\BinaryOperatorParselet;
use MonkeyLang\Lang\Parser\Parselet\CallExpressionParselet;
use MonkeyLang\Lang\Parser\Parselet\FunctionLiteralParselet;
use MonkeyLang\Lang\Parser\Parselet\GroupedExpressionParselet;
use MonkeyLang\Lang\Parser\Parselet\IdentifierParselet;
use MonkeyLang\Lang\Parser\Parselet\IfExpressionParselet;
use MonkeyLang\Lang\Parser\Parselet\IndexExpressionParselet;
use MonkeyLang\Lang\Parser\Parselet\InfixParselet;
use MonkeyLang\Lang\Parser\Parselet\PostfixOperatorParselet;
use MonkeyLang\Lang\Parser\Parselet\PostfixParselet;
use MonkeyLang\Lang\Parser\Parselet\PrefixParselet;
use MonkeyLang\Lang\Parser\Parselet\ScalarParselet;
use MonkeyLang\Lang\Parser\Parselet\UnaryOperatorParselet;
use MonkeyLang\Lang\Parser\Parselet\WhileExpressionParselet;
use MonkeyLang\Lang\Token\Token;
use MonkeyLang\Lang\Token\TokenType;

use function sprintf;

final class Parser
{
    /** @var array<int,string> */
    public array $errors = [];

    /** @var array<int,InfixParselet> */
    public array $infixParselets = [];

    /** @var array<int,PrefixParselet> */
    public array $prefixParselets = [];

    /** @var array<int,PostfixParselet> */
    public array $postfixParselets = [];

    private ?Token $prevToken = null;

    private ?Token $curToken = null;

    private ?Token $peekToken = null;

    public function __construct(
        public readonly Lexer $lexer,
    ) {
        $this->registerPrefix(TokenType::IDENT, new IdentifierParselet($this));
        $this->registerPrefix(TokenType::INT, new ScalarParselet($this));
        $this->registerPrefix(TokenType::FLOAT, new ScalarParselet($this));
        $this->registerPrefix(TokenType::NOT, new UnaryOperatorParselet($this));
        $this->registerPrefix(TokenType::MINUS, new UnaryOperatorParselet($this));
        $this->registerPrefix(TokenType::TRUE, new ScalarParselet($this));
        $this->registerPrefix(TokenType::FALSE, new ScalarParselet($this));
        $this->registerPrefix(TokenType::LPAREN, new GroupedExpressionParselet($this));
        $this->registerPrefix(TokenType::IF, new IfExpressionParselet($this));
        $this->registerPrefix(TokenType::WHILE, new WhileExpressionParselet($this));
        $this->registerPrefix(TokenType::FN, new FunctionLiteralParselet($this));
        $this->registerPrefix(TokenType::STRING, new ScalarParselet($this));
        $this->registerPrefix(TokenType::LBRACKET, new ArrayParselet($this));

        $this->registerInfix(TokenType::PLUS, new BinaryOperatorParselet($this));
        $this->registerInfix(TokenType::MODULO, new BinaryOperatorParselet($this));
        $this->registerInfix(TokenType::POWER, new BinaryOperatorParselet($this));
        $this->registerInfix(TokenType::MINUS, new BinaryOperatorParselet($this));
        $this->registerInfix(TokenType::SLASH, new BinaryOperatorParselet($this));
        $this->registerInfix(TokenType::ASTERISK, new BinaryOperatorParselet($this));
        $this->registerInfix(TokenType::EQ, new BinaryOperatorParselet($this));
        $this->registerInfix(TokenType::NOT_EQ, new BinaryOperatorParselet($this));
        $this->registerInfix(TokenType::LT, new BinaryOperatorParselet($this));
        $this->registerInfix(TokenType::LT_EQ, new BinaryOperatorParselet($this));
        $this->registerInfix(TokenType::GT, new BinaryOperatorParselet($this));
        $this->registerInfix(TokenType::GT_EQ, new BinaryOperatorParselet($this));
        $this->registerInfix(TokenType::AND, new BinaryOperatorParselet($this));
        $this->registerInfix(TokenType::OR, new BinaryOperatorParselet($this));
        $this->registerInfix(TokenType::LBRACKET, new IndexExpressionParselet($this));
        $this->registerInfix(TokenType::LPAREN, new CallExpressionParselet($this));

        $this->registerPostfix(TokenType::PLUS_PLUS, new PostfixOperatorParselet($this));
        $this->registerPostfix(TokenType::MINUS_MINUS, new PostfixOperatorParselet($this));

        $this->nextToken(2);
    }

    public function nextToken(int $times = 1): void
    {
        while ($times-- > 0) {
            $this->prevToken = $this->curToken;
            $this->curToken = $this->peekToken;
            $this->peekToken = $this->lexer->nextToken();
        }
    }

    public function expectPeek(TokenType $type): bool
    {
        if ($this->peekToken()->is($type)) {
            $this->nextToken();

            return true;
        }

        $this->peekError($type);

        return false;
    }

    public function parseExpression(Precedence $precedence): ?Expression
    {
        /** @var PostfixParselet|null $postfixParselet */
        $postfixParselet = $this->postfixParselets[$this->curToken()->type()->value] ?? null;

        if ($postfixParselet instanceof PostfixParselet) {
            return $postfixParselet->parse();
        }

        $prefixParselet = $this->prefixParselets[$this->curToken()->type()->value] ?? null;

        if (!$prefixParselet instanceof PrefixParselet) {
            $this->prefixParserError($this->curToken()->type());

            return null;
        }

        /** @var Expression $leftExpression */
        $leftExpression = $prefixParselet->parse();

        while (!$this->peekToken()->is(TokenType::SEMICOLON) && $precedence->value < $this->peekToken()->type()->precedence()->value) {
            $infixParselet = $this->infixParselets[$this->peekToken()->type()->value] ?? null;

            if (!$infixParselet instanceof InfixParselet) {
                return $leftExpression;
            }

            $this->nextToken();

            /** @var Expression $leftExpression */
            $leftExpression = $infixParselet->parse($leftExpression);
        }

        return $leftExpression;
    }

    /**
     * @return string[]
     */
    public function errors(): array
    {
        return $this->errors;
    }

    public function registerPrefix(TokenType $type, PrefixParselet $prefixParselet): void
    {
        $this->prefixParselets[$type->value] = $prefixParselet;
    }

    public function registerInfix(TokenType $type, InfixParselet $infixParselet): void
    {
        $this->infixParselets[$type->value] = $infixParselet;
    }

    public function registerPostfix(TokenType $type, PostfixParselet $postfixParselet): void
    {
        $this->postfixParselets[$type->value] = $postfixParselet;
    }

    public function peekError(TokenType $type): void
    {
        $this->errors[] = sprintf(
            'expected next token to be %s, got %s instead',
            $type->lexeme(),
            $this->peekToken()->literal(),
        );
    }

    public function curToken(): Token
    {
        /** @var Token $token */
        $token = $this->curToken;

        return $token;
    }

    public function prevToken(): Token
    {
        /** @var Token $token */
        $token = $this->prevToken;

        return $token;
    }

    public function peekToken(): Token
    {
        /** @var Token $token */
        $token = $this->peekToken;

        return $token;
    }

    public function prefixParserError(TokenType $type): void
    {
        $this->errors[] = sprintf(
            'no prefix parse function for %s found',
            $type->lexeme(),
        );
    }
}
