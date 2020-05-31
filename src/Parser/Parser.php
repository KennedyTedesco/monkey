<?php

declare(strict_types=1);

namespace Monkey\Parser;

use Closure;
use Monkey\Ast\Expression;
use Monkey\Ast\Identifier;
use Monkey\Lexer\Lexer;
use Monkey\Token\Token;
use Monkey\Token\TokenType;

final class Parser
{
    /** @psalm-readonly */
    private Lexer $lexer;

    /** @var array<int,string> */
    private array $errors = [];

    /** @var array<int,Closure> */
    private array $prefixParseFns = [];

    /** @var array<int,Closure> */
    private array $infixParseFns = [];

    /**
     * @var Token
     * @psalm-suppress PropertyNotSetInConstructor
     */
    public $curToken;

    /**
     * @var Token
     * @psalm-suppress PropertyNotSetInConstructor
     */
    public $peekToken;

    public function __construct(Lexer $lexer)
    {
        $this->lexer = $lexer;
        $this->nextToken();
        $this->nextToken();

        $this->registerPrefix(
            TokenType::T_IDENT,
            fn () => new Identifier($this->curToken, $this->curToken->literal)
        );
    }

    public function nextToken(): void
    {
        $this->curToken = $this->peekToken;
        $this->peekToken = $this->lexer->nextToken();
    }

    public function curTokenIs(int $type): bool
    {
        return $this->curToken->type === $type;
    }

    public function peekTokenIs(int $type): bool
    {
        return $this->peekToken->type === $type;
    }

    public function expectPeek(int $type): bool
    {
        if ($this->peekTokenIs($type)) {
            $this->nextToken();
            return true;
        }
        $this->peekError($type);
        return false;
    }

    public function peekError(int $type): void
    {
        $this->errors[] = \sprintf(
            'expected next token to be %s, got %s instead',
            TokenType::tokenName($type), $this->peekToken->literal
        );
    }

    public function registerPrefix(int $type, Closure $fn): void
    {
        $this->prefixParseFns[$type] = $fn;
    }

    public function registerInfix(int $type, Closure $fn): void
    {
        $this->infixParseFns[$type] = $fn;
    }

    public function parseExpression(int $precedence): ?Expression
    {
        $prefix = $this->prefixParseFns[$this->curToken->type] ?? null;
        if (null === $prefix) {
            return null;
        }
        return $prefix();
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
