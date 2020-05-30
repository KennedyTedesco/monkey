<?php

declare(strict_types=1);

namespace Monkey\Parser;

use Monkey\Lexer\Lexer;
use Monkey\Token\Token;
use Monkey\Token\TokenType;

final class Parser
{
    /**
     * @psalm-readonly
     */
    private Lexer $lexer;

    private array $errors = [];
    public ?Token $curToken = null;
    public ?Token $peekToken = null;

    public function __construct(Lexer $lexer)
    {
        $this->lexer = $lexer;
        $this->nextToken();
        $this->nextToken();
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

    public function errors(): array
    {
        return $this->errors;
    }
}
