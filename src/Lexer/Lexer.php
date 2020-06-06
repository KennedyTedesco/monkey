<?php

declare(strict_types=1);

namespace Monkey\Lexer;

use Monkey\Token\Token;
use Monkey\Token\TokenType;

final class Lexer
{
    public const EOF = "\0";

    private int $size;
    private string $input;

    private Char $curChar;
    private Char $prevChar;
    private Char $peekChar;

    private int $position = 0;
    private int $readPosition = 0;

    public function __construct(string $input)
    {
        $this->input = $input;
        $this->size = \mb_strlen($input);

        $this->curChar = Char::empty();
        $this->prevChar = Char::empty();
        $this->peekChar = Char::empty();

        $this->readChar();
    }

    public function nextToken(): Token
    {
        $this->skipWhitespaces();

        if ($this->curChar->is(self::EOF)) {
            return Token::from(TokenType::T_EOF, self::EOF);
        }

        if (TokenType::isSingleCharToken($this->curChar->toScalar())) {
            if ($this->curChar->is('=') && $this->peekChar->is('=')) {
                return $this->makeTwoCharTokenAndAdvance(TokenType::T_EQ);
            }

            if ($this->curChar->is('!') && $this->peekChar->is('=')) {
                return $this->makeTwoCharTokenAndAdvance(TokenType::T_NOT_EQ);
            }

            if ($this->curChar->is('>') && $this->peekChar->is('=')) {
                return $this->makeTwoCharTokenAndAdvance(TokenType::T_GT_EQ);
            }

            if ($this->curChar->is('<') && $this->peekChar->is('=')) {
                return $this->makeTwoCharTokenAndAdvance(TokenType::T_LT_EQ);
            }

            /** @var int $tokenType */
            $tokenType = TokenType::lookupToken($this->curChar->toScalar());

            return $this->makeTokenAndAdvance($tokenType, $this->curChar->toScalar());
        }

        if ($this->curChar->isLetter()) {
            $identifier = $this->readIdentifier();
            return Token::from(
                TokenType::lookupToken($identifier) ?? TokenType::T_IDENT,
                $identifier
            );
        }

        if ($this->curChar->isDigit()) {
            return Token::from(TokenType::T_INT, $this->readNumber());
        }

        return $this->makeTokenAndAdvance(TokenType::T_ILLEGAL, $this->curChar->toScalar());
    }

    private function readIdentifier(): string
    {
        $position = $this->position;
        while ($this->curChar->isLetter()) {
            $this->readChar();
        }

        return \mb_substr($this->input, $position, $this->position - $position);
    }

    private function readChar(): void
    {
        $this->prevChar = $this->curChar;

        if ($this->isEnd()) {
            $this->curChar = Char::from(self::EOF);
        } else {
            $this->curChar = Char::from($this->input[$this->readPosition]);
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
        while ($this->curChar->isDigit()) {
            $this->readChar();
        }

        return \mb_substr($this->input, $position, $this->position - $position);
    }

    private function skipWhitespaces(): void
    {
        while ($this->curChar->isWhitespace()) {
            $this->readChar();
        }
    }

    public function isEnd(): bool
    {
        return $this->readPosition >= $this->size;
    }

    private function makeTokenAndAdvance(int $type, string $literal): Token
    {
        $this->readChar();

        return Token::from($type, $literal);
    }

    private function makeTwoCharTokenAndAdvance(int $type): Token
    {
        $this->readChar();

        $token = Token::from(
            $type,
            "{$this->prevChar->toScalar()}{$this->curChar->toScalar()}"
        );

        $this->readChar();

        return $token;
    }
}
