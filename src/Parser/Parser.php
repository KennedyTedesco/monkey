<?php

declare(strict_types=1);

namespace Monkey\Parser;

use Monkey\Ast\Expressions\Expression;
use Monkey\Lexer\Lexer;
use Monkey\Parser\Parselet\BinaryOperatorParselet;
use Monkey\Parser\Parselet\IdentifierParselet;
use Monkey\Parser\Parselet\InfixParselet;
use Monkey\Parser\Parselet\LiteralParselet;
use Monkey\Parser\Parselet\PrefixOperatorParselet;
use Monkey\Parser\Parselet\PrefixParselet;
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
    ];

    public function __construct(Lexer $lexer)
    {
        $this->lexer = $lexer;

        $this->registerPrefixParselet(TokenType::T_IDENT, new IdentifierParselet($this));
        $this->registerPrefixParselet(TokenType::T_INT, new LiteralParselet($this));
        $this->registerPrefixParselet(TokenType::T_BANG, new PrefixOperatorParselet($this));
        $this->registerPrefixParselet(TokenType::T_MINUS, new PrefixOperatorParselet($this));
        $this->registerPrefixParselet(TokenType::T_TRUE, new LiteralParselet($this));
        $this->registerPrefixParselet(TokenType::T_FALSE, new LiteralParselet($this));

        $this->registerInfixParselet(TokenType::T_PLUS, new BinaryOperatorParselet($this));
        $this->registerInfixParselet(TokenType::T_MINUS, new BinaryOperatorParselet($this));
        $this->registerInfixParselet(TokenType::T_SLASH, new BinaryOperatorParselet($this));
        $this->registerInfixParselet(TokenType::T_ASTERISK, new BinaryOperatorParselet($this));
        $this->registerInfixParselet(TokenType::T_EQ, new BinaryOperatorParselet($this));
        $this->registerInfixParselet(TokenType::T_NOT_EQ, new BinaryOperatorParselet($this));
        $this->registerInfixParselet(TokenType::T_LT, new BinaryOperatorParselet($this));
        $this->registerInfixParselet(TokenType::T_GT, new BinaryOperatorParselet($this));

        $this->nextToken();
        $this->nextToken();
    }

    public function nextToken(): void
    {
        $this->curToken = $this->peekToken;
        $this->peekToken = $this->lexer->nextToken();
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
        $this->errors[] = \safe\sprintf(
            'expected next token to be %s, got %s instead',
            TokenType::name($type), $this->peekToken->literal()
        );
    }

    public function prefixParserError(int $type): void
    {
        $this->errors[] = \safe\sprintf(
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

    /**
     * @return array<int,string>
     */
    public function errors(): array
    {
        return $this->errors;
    }
}
