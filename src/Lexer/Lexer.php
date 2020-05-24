<?php

declare(strict_types=1);

namespace Monkey\Lexer;

use Monkey\Token\Literal;
use Monkey\Token\Token;
use Monkey\Token\TokenTypes;
use Monkey\Token\Type;

final class Lexer
{
    private const EOF = '0';

    /**
     * @psalm-readonly
     */
    private int $size;

    /**
     * @psalm-readonly
     */
    private string $input;

    private string $ch;
    private int $position;
    private int $readPosition;

    public function __construct(string $input)
    {
        $this->input = $input;
        $this->size = \mb_strlen($this->input);

        $this->position = 0;
        $this->readPosition = 0;

        $this->readChar();
    }

    public function nextToken(): Token
    {
        $this->skipWhitespaces();

        if ($this->charIs(self::EOF)) {
            return $this->makeTokenAndAdvance(
                new Type(TokenTypes::T_EOF),
                new Literal('')
            );
        }

        if (TokenTypes::isSingleCharToken($this->ch)) {
            if ($this->charIs('=') && $this->peekCharIs('=')) {
                return $this->makeTwoCharTokenAndAdvance(new Type(TokenTypes::T_EQ));
            }

            if ($this->charIs('!') && $this->peekCharIs('=')) {
                return $this->makeTwoCharTokenAndAdvance(new Type(TokenTypes::T_NOT_EQ));
            }

            if ($this->charIs('>') && $this->peekCharIs('=')) {
                return $this->makeTwoCharTokenAndAdvance(new Type(TokenTypes::T_GT_EQ));
            }

            if ($this->charIs('<') && $this->peekCharIs('=')) {
                return $this->makeTwoCharTokenAndAdvance(new Type(TokenTypes::T_LT_EQ));
            }

            return $this->makeTokenAndAdvance(
                new Type($this->ch),
                new Literal($this->ch)
            );
        }

        if ($this->isLetter($this->ch)) {
            $ident = $this->readIdentifier();

            return $this->makeToken(
                new Type(TokenTypes::lookupIdentifier($ident)),
                new Literal($ident)
            );
        }

        if ($this->isDigit($this->ch)) {
            return $this->makeToken(
                new Type(TokenTypes::T_INT),
                new Literal($this->readNumber())
            );
        }

        return $this->makeTokenAndAdvance(
            new Type(TokenTypes::T_ILLEGAL),
            new Literal($this->ch)
        );
    }

    private function readIdentifier(): string
    {
        $position = $this->position;
        while ($this->isLetter($this->ch)) {
            $this->readChar();
        }

        return \mb_substr($this->input, $position, $this->position - $position);
    }

    private function readChar(): void
    {
        if ($this->isEnd()) {
            $this->ch = self::EOF;
        } else {
            $this->ch = $this->input[$this->readPosition];
        }

        $this->position = $this->readPosition;
        ++$this->readPosition;
    }

    private function readNumber(): string
    {
        $position = $this->position;
        while ($this->isDigit($this->ch)) {
            $this->readChar();
        }

        return \mb_substr($this->input, $position, $this->position - $position);
    }

    private function skipWhitespaces(): void
    {
        while ($this->isWhitespace($this->ch)) {
            $this->readChar();
        }
    }

    private function isWhitespace(string $ch): bool
    {
        return \ctype_space($ch);
    }

    private function isLetter(string $ch): bool
    {
        return '_' === $ch || \ctype_alpha($ch);
    }

    private function isDigit(string $ch): bool
    {
        return \ctype_digit($ch);
    }

    private function isEnd(): bool
    {
        return $this->readPosition >= $this->size;
    }

    private function charIs(string $ch): bool
    {
        return $ch === $this->ch;
    }

    private function peekCharIs(string $ch): bool
    {
        return $this->input[$this->readPosition] === $ch;
    }

    private function makeTokenAndAdvance(Type $type, Literal $literal): Token
    {
        $this->readChar();
        return $this->makeToken($type, $literal);
    }

    private function makeTwoCharTokenAndAdvance(Type $type): Token
    {
        $ch = $this->ch;
        $this->readChar();
        $token = $this->makeToken($type, new Literal("{$ch}{$this->ch}"));
        $this->readChar();
        return $token;
    }

    private function makeToken(Type $type, Literal $literal): Token
    {
        return new Token($type, $literal);
    }
}
