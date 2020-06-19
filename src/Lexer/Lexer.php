<?php

declare(strict_types=1);

namespace Monkey\Lexer;

use Monkey\Token\Token;
use Monkey\Token\TokenType;

final class Lexer
{
    public const EOF = "\0";

    private Input $input;
    private Char $curChar;
    private Char $prevChar;
    private Char $peekChar;

    private int $position = 0;
    private int $readPosition = 0;

    public function __construct(string $input)
    {
        $this->input = new Input($input);
        $this->curChar = Char::empty();
        $this->prevChar = Char::empty();
        $this->peekChar = Char::empty();

        $this->readChar();
    }

    public function nextToken(): Token
    {
        $this->skipWhitespaces();

        if (TokenType::isSingleCharToken($this->curChar->toScalar())) {
            switch (true) {
                case $this->curChar->is('=') && $this->peekChar->is('='):
                    return $this->makeTwoCharTokenAndAdvance(TokenType::T_EQ);
                case $this->curChar->is('!') && $this->peekChar->is('='):
                    return $this->makeTwoCharTokenAndAdvance(TokenType::T_NOT_EQ);
                case $this->curChar->is('>') && $this->peekChar->is('='):
                    return $this->makeTwoCharTokenAndAdvance(TokenType::T_GT_EQ);
                case $this->curChar->is('<') && $this->peekChar->is('='):
                    return $this->makeTwoCharTokenAndAdvance(TokenType::T_LT_EQ);
                default:
                    return $this->makeTokenAndAdvance(
                        TokenType::lookupToken($this->curChar->toScalar()),
                        $this->curChar->toScalar()
                    );
            }
        }

        switch (true) {
            case $this->curChar->is('"'):
                return $this->makeTokenAndAdvance(TokenType::T_STRING, $this->readString());
            case $this->curChar->isLetter():
                return Token::from(
                    TokenType::lookupToken($identifier = $this->readIdentifier()) ?? TokenType::T_IDENT,
                    $identifier
                );
            case $this->curChar->isDigit():
                return Token::from(TokenType::T_INT, $this->readNumber());
            case $this->curChar->is(self::EOF):
                return Token::from(TokenType::T_EOF, self::EOF);
            default:
                return $this->makeTokenAndAdvance(TokenType::T_ILLEGAL, $this->curChar->toScalar());
        }
    }

    private function readIdentifier(): string
    {
        $position = $this->position;
        while ($this->curChar->isLetter()) {
            $this->readChar();
        }
        return $this->input->substr($position, $this->position - $position);
    }

    private function readChar(): void
    {
        $this->prevChar = $this->curChar;

        if ($this->isEnd()) {
            $this->curChar = Char::from(self::EOF);
        } else {
            $this->curChar = Char::from($this->input->char($this->readPosition));
        }

        $this->position = $this->readPosition;
        ++$this->readPosition;

        if (!$this->isEnd()) {
            $this->peekChar = Char::from($this->input->char($this->readPosition));
        }
    }

    private function readNumber(): string
    {
        $position = $this->position;
        while ($this->curChar->isDigit()) {
            $this->readChar();
        }
        return $this->input->substr($position, $this->position - $position);
    }

    private function readString(): string
    {
        $position = $this->position + 1;
        while (true) {
            $this->readChar();
            if ($this->curChar->is('"') || $this->isEnd()) {
                break;
            }
        }
        return $this->input->substr($position, $this->position - $position);
    }

    private function skipWhitespaces(): void
    {
        while ($this->curChar->isWhitespace()) {
            $this->readChar();
        }
    }

    public function isEnd(): bool
    {
        return $this->readPosition >= $this->input->size();
    }

    private function makeTokenAndAdvance(int $type, string $literal): Token
    {
        $this->readChar();

        return Token::from($type, $literal);
    }

    private function makeTwoCharTokenAndAdvance(int $type): Token
    {
        $this->readChar();
        $token = Token::from($type, "{$this->prevChar->toScalar()}{$this->curChar->toScalar()}");
        $this->readChar();

        return $token;
    }
}
