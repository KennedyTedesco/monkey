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
    public array $errors = [];

    /** @var array<int,InfixParselet> */
    public array $infixParselets = [];

    /** @var array<int,PrefixParselet> */
    public array $prefixParselets = [];

    /** @var array<int,PrefixParselet> */
    public array $postfixParselets = [];

    /** @var array<int, Precedence> */
    public array $precedences = [
        TokenType::EQ->value => Precedence::EQUALS,
        TokenType::NOT_EQ->value => Precedence::EQUALS,
        TokenType::LT->value => Precedence::LESS_GREATER,
        TokenType::LT_EQ->value => Precedence::LESS_GREATER,
        TokenType::GT->value => Precedence::LESS_GREATER,
        TokenType::GT_EQ->value => Precedence::LESS_GREATER,
        TokenType::PLUS->value => Precedence::SUM,
        TokenType::MINUS->value => Precedence::SUM,
        TokenType::SLASH->value => Precedence::PRODUCT,
        TokenType::ASTERISK->value => Precedence::PRODUCT,
        TokenType::MODULO->value => Precedence::PRODUCT,
        TokenType::LPAREN->value => Precedence::CALL,
        TokenType::LBRACKET->value => Precedence::INDEX,
        TokenType::AND->value => Precedence::AND,
        TokenType::OR->value => Precedence::OR,
        TokenType::POWER->value => Precedence::POWER,
    ];

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
        if ($this->peekToken->is($type)) {
            $this->nextToken();

            return true;
        }

        $this->peekError($type);

        return false;
    }

    public function parseExpression(Precedence $precedence): ?Expression
    {
        /** @var PostfixParselet|null $postfixParselet */
        $postfixParselet = $this->postfixParselets[$this->curToken->type()->value] ?? null;

        if ($postfixParselet instanceof PostfixParselet) {
            return $postfixParselet->parse();
        }

        $prefixParselet = $this->prefixParselets[$this->curToken->type()->value] ?? null;

        if (!$prefixParselet instanceof PrefixParselet) {
            $this->prefixParserError($this->curToken->type());

            return null;
        }

        /** @var Expression $leftExpression */
        $leftExpression = $prefixParselet->parse();

        while (!$this->peekToken->is(TokenType::SEMICOLON) && $precedence->value < $this->precedence($this->peekToken)->value) {
            $infixParselet = $this->infixParselets[$this->peekToken->type()->value] ?? null;

            if (!$infixParselet instanceof InfixParselet) {
                return $leftExpression;
            }

            $this->nextToken();

            $leftExpression = $infixParselet->parse($leftExpression);
        }

        return $leftExpression;
    }

    public function precedence(Token $token): Precedence
    {
        return $this->precedences[$token->type()->value] ?? Precedence::LOWEST;
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
            $this->peekToken->literal(),
        );
    }

    public function prefixParserError(TokenType $type): void
    {
        $this->errors[] = sprintf(
            'no prefix parse function for %s found',
            $type->lexeme(),
        );
    }
}
