<?php

declare(strict_types=1);

namespace Monkey\Parser;

use Monkey\Ast\Expression;
use Monkey\Lexer\Lexer;
use Monkey\Parser\Parselet\IdentifierParselet;
use Monkey\Parser\Parselet\Parselet;
use Monkey\Token\Token;
use Monkey\Token\TokenType;

final class Parser
{
    /** @psalm-readonly */
    private Lexer $lexer;

    /** @var array<int,string> */
    private array $errors = [];

    /** @var array<int,Parselet> */
    private array $prefixParselets = [];

    /** @var array<int,Parselet> */
    private array $infixParselets = [];

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

        $this->registerPrefixParselet(TokenType::T_IDENT, new IdentifierParselet($this));
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

    public function registerPrefixParselet(int $type, Parselet $parselet): void
    {
        $this->prefixParselets[$type] = $parselet;
    }

    public function registerInfixParselet(int $type, Parselet $parselet): void
    {
        $this->infixParselets[$type] = $parselet;
    }

    public function parseExpression(int $precedence): ?Expression
    {
        /** @var Parselet|null $prefix */
        $prefix = $this->prefixParselets[$this->curToken->type] ?? null;
        if (null === $prefix) {
            return null;
        }
        return $prefix->parse();
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
