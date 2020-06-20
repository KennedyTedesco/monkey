<?php

declare(strict_types=1);

namespace Monkey\Parser;

use Monkey\Ast\Expressions\Expression;
use Monkey\Lexer\Lexer;
use Monkey\Parser\Parselet\BinaryOperatorParselet;
use Monkey\Parser\Parselet\CallExpressionParselet;
use Monkey\Parser\Parselet\FunctionLiteralParselet;
use Monkey\Parser\Parselet\GroupedExpressionParselet;
use Monkey\Parser\Parselet\IdentifierParselet;
use Monkey\Parser\Parselet\IfExpressionParselet;
use Monkey\Parser\Parselet\InfixParselet;
use Monkey\Parser\Parselet\PrefixParselet;
use Monkey\Parser\Parselet\ScalarParselet;
use Monkey\Parser\Parselet\UnaryOperatorParselet;
use Monkey\Token\Token;
use Monkey\Token\TokenType;

final class Parser
{
    private Lexer $lexer;

    /** @var Token */
    public $curToken;

    /** @var Token */
    public $peekToken;

    /** @var array<int,string> */
    private array $errors = [];

    /** @var array<int,PrefixParselet> */
    private array $prefixParselets = [];

    /** @var array<int,InfixParselet> */
    private array $infixParselets = [];

    /** @var array<int,int> */
    private array $precedences = [
        TokenType::T_EQ => Precedence::EQUALS,
        TokenType::T_NOT_EQ => Precedence::EQUALS,
        TokenType::T_LT => Precedence::LESSGREATER,
        TokenType::T_LT_EQ => Precedence::LESSGREATER,
        TokenType::T_GT => Precedence::LESSGREATER,
        TokenType::T_GT_EQ => Precedence::LESSGREATER,
        TokenType::T_PLUS => Precedence::SUM,
        TokenType::T_MINUS => Precedence::SUM,
        TokenType::T_SLASH => Precedence::PRODUCT,
        TokenType::T_ASTERISK => Precedence::PRODUCT,
        TokenType::T_LPAREN => Precedence::CALL,
    ];

    public function __construct(Lexer $lexer)
    {
        $this->lexer = $lexer;

        $this->registerPrefixParselet(TokenType::T_IDENT, new IdentifierParselet($this));
        $this->registerPrefixParselet(TokenType::T_INT, new ScalarParselet($this));
        $this->registerPrefixParselet(TokenType::T_BANG, new UnaryOperatorParselet($this));
        $this->registerPrefixParselet(TokenType::T_MINUS, new UnaryOperatorParselet($this));
        $this->registerPrefixParselet(TokenType::T_TRUE, new ScalarParselet($this));
        $this->registerPrefixParselet(TokenType::T_FALSE, new ScalarParselet($this));
        $this->registerPrefixParselet(TokenType::T_LPAREN, new GroupedExpressionParselet($this));
        $this->registerPrefixParselet(TokenType::T_IF, new IfExpressionParselet($this));
        $this->registerPrefixParselet(TokenType::T_FN, new FunctionLiteralParselet($this));
        $this->registerPrefixParselet(TokenType::T_STRING, new ScalarParselet($this));

        $this->registerInfixParselet(TokenType::T_PLUS, new BinaryOperatorParselet($this));
        $this->registerInfixParselet(TokenType::T_MINUS, new BinaryOperatorParselet($this));
        $this->registerInfixParselet(TokenType::T_SLASH, new BinaryOperatorParselet($this));
        $this->registerInfixParselet(TokenType::T_ASTERISK, new BinaryOperatorParselet($this));
        $this->registerInfixParselet(TokenType::T_EQ, new BinaryOperatorParselet($this));
        $this->registerInfixParselet(TokenType::T_NOT_EQ, new BinaryOperatorParselet($this));
        $this->registerInfixParselet(TokenType::T_LT, new BinaryOperatorParselet($this));
        $this->registerInfixParselet(TokenType::T_LT_EQ, new BinaryOperatorParselet($this));
        $this->registerInfixParselet(TokenType::T_GT, new BinaryOperatorParselet($this));
        $this->registerInfixParselet(TokenType::T_GT_EQ, new BinaryOperatorParselet($this));

        $this->registerInfixParselet(TokenType::T_LPAREN, new CallExpressionParselet($this));

        $this->nextToken(2);
    }

    public function nextToken(int $times = 1): void
    {
        while ($times-- > 0) {
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
        /** @var PrefixParselet|null $prefixParser */
        $prefixParser = $this->prefixParselets[$this->curToken->type()] ?? null;
        if (null === $prefixParser) {
            $this->prefixParserError($this->curToken->type());

            return null;
        }

        /** @var Expression $leftExpression */
        $leftExpression = $prefixParser->parse();

        while (!$this->peekToken->is(TokenType::T_SEMICOLON) && $precedence < $this->precedence($this->peekToken)) {
            /** @var InfixParselet|null $infixParser */
            $infixParser = $this->infixParselets[$this->peekToken->type()] ?? null;
            if (null === $infixParser) {
                return $leftExpression;
            }

            $this->nextToken();

            $leftExpression = $infixParser->parse($leftExpression);
        }

        return $leftExpression;
    }

    public function peekError(int $type): void
    {
        $this->errors[] = \sprintf(
            'expected next token to be %s, got %s instead',
            TokenType::name($type), $this->peekToken->literal()
        );
    }

    public function prefixParserError(int $type): void
    {
        $this->errors[] = \sprintf(
            'no prefix parse function for %s found', TokenType::name($type)
        );
    }

    public function registerPrefixParselet(int $type, PrefixParselet $parselet): void
    {
        $this->prefixParselets[$type] = $parselet;
    }

    public function registerInfixParselet(int $type, InfixParselet $parselet): void
    {
        $this->infixParselets[$type] = $parselet;
    }

    public function precedence(Token $token): int
    {
        return $this->precedences[$token->type()] ?? Precedence::LOWEST;
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
