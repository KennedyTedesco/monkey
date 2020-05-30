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

    private array $errors;
    public Token $curToken;
    public Token $peekToken;

    public function __construct(Lexer $lexer)
    {
        $this->lexer = $lexer;
    }

    public function nextToken(): void
    {
        $this->curToken = $this->peekToken;
        $this->peekToken = $this->lexer->nextToken();
    }

    public function curTokenIs(TokenType $type): bool
    {
        return $this->curToken->type->value === $type->value;
    }

    public function peekTokenIs(TokenType $type): bool
    {
        return $this->peekToken->type->value === $type->value;
    }

    public function expectPeek(TokenType $type): bool
    {
        if ($this->peekTokenIs($type)) {
            $this->nextToken();
            return true;
        }
        $this->peekError($type);
        return false;
    }

    public function peekError(TokenType $type): void
    {
        $this->errors[] = \sprintf(
            'expected next token to be %s, got %s instead',
            $type->value, $this->peekToken->type->value
        );
    }
}
