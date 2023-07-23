<?php

declare(strict_types=1);

namespace Monkey\Lexer;

use Monkey\Token\Token;
use Monkey\Token\TokenType;

final class Lexer
{
    public const EOF = "\0";

    public readonly Input $input;

    public Char $curChar;

    public Char $prevChar;

    public Char $peekChar;

    public int $position = 0;

    public int $readPosition = 0;

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

        return match (true) {
            $this->curAndPeekCharIs('**') => $this->makeTwoCharTokenAndAdvance(TokenType::POWER),

            $this->curAndPeekCharIs('==') => $this->makeTwoCharTokenAndAdvance(TokenType::EQ),

            $this->curAndPeekCharIs('!=') => $this->makeTwoCharTokenAndAdvance(TokenType::NOT_EQ),

            $this->curAndPeekCharIs('>=') => $this->makeTwoCharTokenAndAdvance(TokenType::GT_EQ),

            $this->curAndPeekCharIs('<=') => $this->makeTwoCharTokenAndAdvance(TokenType::LT_EQ),

            $this->curAndPeekCharIs('&&') => $this->makeTwoCharTokenAndAdvance(TokenType::AND),

            $this->curAndPeekCharIs('||') => $this->makeTwoCharTokenAndAdvance(TokenType::OR),

            $this->curAndPeekCharIs('++') => $this->makeTwoCharTokenAndAdvance(TokenType::PLUS_PLUS),

            $this->curAndPeekCharIs('--') => $this->makeTwoCharTokenAndAdvance(TokenType::MINUS_MINUS),

            $this->curChar->is('"') => $this->makeTokenAndAdvance(TokenType::STRING, $this->readString()),

            $this->curChar->isLetter() => Token::from(
                TokenType::lookupToken($identifier = $this->readIdentifier()) ?? TokenType::IDENT,
                $identifier,
            ),

            $this->curChar->isDigit() => Token::from(ctype_digit($number = $this->readNumber()) ? TokenType::INT : TokenType::FLOAT, $number),

            $this->curChar->is(self::EOF) => Token::from(TokenType::EOF, self::EOF),

            TokenType::isSingleCharToken($this->curChar->toScalar()) => $this->makeTokenAndAdvance(
                TokenType::lookupToken($this->curChar->toScalar()),
                $this->curChar->toScalar(),
            ),

            default => $this->makeTokenAndAdvance(TokenType::ILLEGAL, $this->curChar->toScalar()),
        };
    }

    public function readChar(): void
    {
        $this->prevChar = $this->curChar;

        $this->curChar = $this->isEnd() ? Char::from(self::EOF) : Char::from($this->input->char($this->readPosition));

        $this->position = $this->readPosition;
        $this->readPosition++;

        if (!$this->isEnd()) {
            $this->peekChar = Char::from($this->input->char($this->readPosition));
        }
    }

    public function skipWhitespaces(): void
    {
        while ($this->curChar->isWhitespace()) {
            $this->readChar();
        }
    }

    public function curAndPeekCharIs(string $operators): bool
    {
        if (!$this->curChar->is($operators[0])) {
            return false;
        }

        return $this->peekChar->is($operators[1]);
    }

    public function makeTwoCharTokenAndAdvance(TokenType $type): Token
    {
        $this->readChar();
        $token = Token::from($type, "{$this->prevChar->toScalar()}{$this->curChar->toScalar()}");
        $this->readChar();

        return $token;
    }

    public function makeTokenAndAdvance(TokenType $type, string $literal): Token
    {
        $this->readChar();

        return Token::from($type, $literal);
    }

    public function readString(): string
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

    public function isEnd(): bool
    {
        return $this->readPosition >= $this->input->size();
    }

    public function readIdentifier(): string
    {
        $position = $this->position;

        while ($this->curChar->isAlphanumeric()) {
            $this->readChar();
        }

        return $this->input->substr($position, $this->position - $position);
    }

    public function readNumber(): string
    {
        $position = $this->position;

        while ($this->curChar->isDigit() || $this->curChar->is('.')) {
            $this->readChar();
        }

        return $this->input->substr($position, $this->position - $position);
    }
}
