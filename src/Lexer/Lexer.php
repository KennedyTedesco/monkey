<?php

declare(strict_types=1);

namespace Monkey\Lexer;

use Monkey\Token\Literal;
use Monkey\Token\Token;
use Monkey\Token\TokenTypes;
use Monkey\Token\Type;

final class Lexer
{
    private const EOF = '-1';

    /**
     * @psalm-readonly
     */
    private int $size;

    /**
     * @psalm-readonly
     */
    private string $input;

    private Char $char;
    private Char $peekChar;

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

        if ($this->char->is(self::EOF)) {
            return $this->makeTokenAndAdvance(
                new Type(TokenTypes::T_EOF),
                new Literal('')
            );
        }

        if (TokenTypes::isSingleCharToken($this->char->toScalar())) {
            if ($this->char->is('=') && $this->peekChar->is('=')) {
                return $this->makeTwoCharTokenAndAdvance(new Type(TokenTypes::T_EQ));
            }

            if ($this->char->is('!') && $this->peekChar->is('=')) {
                return $this->makeTwoCharTokenAndAdvance(new Type(TokenTypes::T_NOT_EQ));
            }

            if ($this->char->is('>') && $this->peekChar->is('=')) {
                return $this->makeTwoCharTokenAndAdvance(new Type(TokenTypes::T_GT_EQ));
            }

            if ($this->char->is('<') && $this->peekChar->is('=')) {
                return $this->makeTwoCharTokenAndAdvance(new Type(TokenTypes::T_LT_EQ));
            }

            return $this->makeTokenAndAdvance(
                new Type($this->char->toScalar()),
                new Literal($this->char->toScalar())
            );
        }

        if ($this->char->isLetter()) {
            $ident = $this->readIdentifier();

            return $this->makeToken(
                new Type(TokenTypes::lookupIdentifier($ident)),
                new Literal($ident)
            );
        }

        if ($this->char->isDigit()) {
            return $this->makeToken(
                new Type(TokenTypes::T_INT),
                new Literal($this->readNumber())
            );
        }

        return $this->makeTokenAndAdvance(
            new Type(TokenTypes::T_ILLEGAL),
            new Literal($this->char->toScalar())
        );
    }

    private function readIdentifier(): string
    {
        $position = $this->position;
        while ($this->char->isLetter()) {
            $this->readChar();
        }

        return \mb_substr($this->input, $position, $this->position - $position);
    }

    private function readChar(): void
    {
        if ($this->isEnd()) {
            $this->char = Char::from(self::EOF);
        } else {
            $this->char = Char::from($this->input[$this->readPosition]);
        }

        $this->position = $this->readPosition;
        ++$this->readPosition;

        if (!$this->isEnd()) {
            $this->peekChar = Char::from($this->input[$this->readPosition]);
        }
    }

    private function readNumber(): string
    {
        $position = $this->position;
        while ($this->char->isDigit()) {
            $this->readChar();
        }

        return \mb_substr($this->input, $position, $this->position - $position);
    }

    private function skipWhitespaces(): void
    {
        while ($this->char->isWhitespace()) {
            $this->readChar();
        }
    }

    private function isEnd(): bool
    {
        return $this->readPosition >= $this->size;
    }

    private function makeTokenAndAdvance(Type $type, Literal $literal): Token
    {
        $this->readChar();
        return $this->makeToken($type, $literal);
    }

    private function makeTwoCharTokenAndAdvance(Type $type): Token
    {
        $char = $this->char;
        $this->readChar();
        $token = $this->makeToken($type, new Literal("{$char->toScalar()}{$this->char->toScalar()}"));
        $this->readChar();
        return $token;
    }

    private function makeToken(Type $type, Literal $literal): Token
    {
        return new Token($type, $literal);
    }
}
