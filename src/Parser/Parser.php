<?php

declare(strict_types=1);

namespace Monkey\Parser;

use Monkey\Ast\Expressions\Expression;
use Monkey\Lexer\Lexer;
use Monkey\Parser\Parselet\ArrayParselet;
use Monkey\Parser\Parselet\BinaryOperatorParselet;
use Monkey\Parser\Parselet\CallExpressionParselet;
use Monkey\Parser\Parselet\FunctionLiteralParselet;
use Monkey\Parser\Parselet\GroupedExpressionParselet;
use Monkey\Parser\Parselet\IdentifierParselet;
use Monkey\Parser\Parselet\IfExpressionParselet;
use Monkey\Parser\Parselet\IndexExpressionParselet;
use Monkey\Parser\Parselet\InfixParselet;
use Monkey\Parser\Parselet\PostfixOperatorParselet;
use Monkey\Parser\Parselet\PostfixParselet;
use Monkey\Parser\Parselet\PrefixParselet;
use Monkey\Parser\Parselet\ScalarParselet;
use Monkey\Parser\Parselet\UnaryOperatorParselet;
use Monkey\Parser\Parselet\WhileExpressionParselet;
use Monkey\Token\Token;
use Monkey\Token\TokenType;

final class Parser
{
    public ?Token $prevToken = null;

    public ?Token $curToken = null;

    public ?Token $peekToken = null;

    /** @var array<int,string> */
    private array $errors = [];

    /** @var array<int,InfixParselet> */
    private array $infixParselets = [];

    /** @var array<int,PrefixParselet> */
    private array $prefixParselets = [];

    /** @var array<int,PrefixParselet> */
    private array $postfixParselets = [];

    /** @var array<int,int> */
    private array $precedences = [
        TokenType::T_EQ => Precedence::EQUALS,
        TokenType::T_NOT_EQ => Precedence::EQUALS,
        TokenType::T_LT => Precedence::LESS_GREATER,
        TokenType::T_LT_EQ => Precedence::LESS_GREATER,
        TokenType::T_GT => Precedence::LESS_GREATER,
        TokenType::T_GT_EQ => Precedence::LESS_GREATER,
        TokenType::T_PLUS => Precedence::SUM,
        TokenType::T_MINUS => Precedence::SUM,
        TokenType::T_SLASH => Precedence::PRODUCT,
        TokenType::T_ASTERISK => Precedence::PRODUCT,
        TokenType::T_MODULO => Precedence::PRODUCT,
        TokenType::T_LPAREN => Precedence::CALL,
        TokenType::T_LBRACKET => Precedence::INDEX,
        TokenType::T_AND => Precedence::AND,
        TokenType::T_OR => Precedence::OR,
        TokenType::T_POWER => Precedence::POWER,
    ];

    public function __construct(private Lexer $lexer)
    {
        $this->registerPrefix(TokenType::T_IDENT, new IdentifierParselet($this));
        $this->registerPrefix(TokenType::T_INT, new ScalarParselet($this));
        $this->registerPrefix(TokenType::T_FLOAT, new ScalarParselet($this));
        $this->registerPrefix(TokenType::T_NOT, new UnaryOperatorParselet($this));
        $this->registerPrefix(TokenType::T_MINUS, new UnaryOperatorParselet($this));
        $this->registerPrefix(TokenType::T_TRUE, new ScalarParselet($this));
        $this->registerPrefix(TokenType::T_FALSE, new ScalarParselet($this));
        $this->registerPrefix(TokenType::T_LPAREN, new GroupedExpressionParselet($this));
        $this->registerPrefix(TokenType::T_IF, new IfExpressionParselet($this));
        $this->registerPrefix(TokenType::T_WHILE, new WhileExpressionParselet($this));
        $this->registerPrefix(TokenType::T_FN, new FunctionLiteralParselet($this));
        $this->registerPrefix(TokenType::T_STRING, new ScalarParselet($this));
        $this->registerPrefix(TokenType::T_LBRACKET, new ArrayParselet($this));

        $this->registerInfix(TokenType::T_PLUS, new BinaryOperatorParselet($this));
        $this->registerInfix(TokenType::T_MODULO, new BinaryOperatorParselet($this));
        $this->registerInfix(TokenType::T_POWER, new BinaryOperatorParselet($this));
        $this->registerInfix(TokenType::T_MINUS, new BinaryOperatorParselet($this));
        $this->registerInfix(TokenType::T_SLASH, new BinaryOperatorParselet($this));
        $this->registerInfix(TokenType::T_ASTERISK, new BinaryOperatorParselet($this));
        $this->registerInfix(TokenType::T_EQ, new BinaryOperatorParselet($this));
        $this->registerInfix(TokenType::T_NOT_EQ, new BinaryOperatorParselet($this));
        $this->registerInfix(TokenType::T_LT, new BinaryOperatorParselet($this));
        $this->registerInfix(TokenType::T_LT_EQ, new BinaryOperatorParselet($this));
        $this->registerInfix(TokenType::T_GT, new BinaryOperatorParselet($this));
        $this->registerInfix(TokenType::T_GT_EQ, new BinaryOperatorParselet($this));
        $this->registerInfix(TokenType::T_AND, new BinaryOperatorParselet($this));
        $this->registerInfix(TokenType::T_OR, new BinaryOperatorParselet($this));
        $this->registerInfix(TokenType::T_LBRACKET, new IndexExpressionParselet($this));
        $this->registerInfix(TokenType::T_LPAREN, new CallExpressionParselet($this));

        $this->registerPostfix(TokenType::T_PLUS_PLUS, new PostfixOperatorParselet($this));
        $this->registerPostfix(TokenType::T_MINUS_MINUS, new PostfixOperatorParselet($this));

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

    public function expectPeek(int $type): bool
    {
        if ($this->peekToken->is($type)) {
            $this->nextToken();

            return true;
        }

        $this->peekError($type);

        return false;
    }

    public function parseExpression(int $precedence): ?Expression
    {
        /** @var PostfixParselet|null $postfixParselet */
        $postfixParselet = $this->postfixParselets[$this->curToken->type()] ?? null;
        if (null !== $postfixParselet) {
            return $postfixParselet->parse();
        }

        /** @var PrefixParselet|null $prefixParselet */
        $prefixParselet = $this->prefixParselets[$this->curToken->type()] ?? null;
        if (null === $prefixParselet) {
            $this->prefixParserError($this->curToken->type());

            return null;
        }

        /** @var Expression $leftExpression */
        $leftExpression = $prefixParselet->parse();

        while (!$this->peekToken->is(TokenType::T_SEMICOLON) && $precedence < $this->precedence($this->peekToken)) {
            /** @var InfixParselet|null $infixParselet */
            $infixParselet = $this->infixParselets[$this->peekToken->type()] ?? null;
            if (null === $infixParselet) {
                return $leftExpression;
            }

            $this->nextToken();

            $leftExpression = $infixParselet->parse($leftExpression);
        }

        return $leftExpression;
    }

    public function precedence(Token $token): int
    {
        return $this->precedences[$token->type()] ?? Precedence::LOWEST;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    private function registerPrefix(int $type, PrefixParselet $parselet): void
    {
        $this->prefixParselets[$type] = $parselet;
    }

    private function registerInfix(int $type, InfixParselet $parselet): void
    {
        $this->infixParselets[$type] = $parselet;
    }

    private function registerPostfix(int $type, PostfixParselet $parselet): void
    {
        $this->postfixParselets[$type] = $parselet;
    }

    private function prefixParserError(int $type): void
    {
        $this->errors[] = sprintf(
            'no prefix parse function for %s found', TokenType::lexeme($type)
        );
    }

    private function peekError(int $type): void
    {
        $this->errors[] = sprintf(
            'expected next token to be %s, got %s instead',
            TokenType::lexeme($type), $this->peekToken->literal()
        );
    }
}
