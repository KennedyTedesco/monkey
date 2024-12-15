<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Lexer;

use MonkeyLang\Lang\Token\Token;
use MonkeyLang\Lang\Token\TokenType;

final class Lexer
{
    public const string EOF = "\0";

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
            $this->curChar->is('*') && $this->peekChar->is('*') => $this->makeTwoCharTokenAndAdvance(TokenType::POWER),

            $this->curChar->is('=') && $this->peekChar->is('=') => $this->makeTwoCharTokenAndAdvance(TokenType::EQ),

            $this->curChar->is('!') && $this->peekChar->is('=') => $this->makeTwoCharTokenAndAdvance(TokenType::NOT_EQ),

            $this->curChar->is('>') && $this->peekChar->is('=') => $this->makeTwoCharTokenAndAdvance(TokenType::GT_EQ),

            $this->curChar->is('<') && $this->peekChar->is('=') => $this->makeTwoCharTokenAndAdvance(TokenType::LT_EQ),

            $this->curChar->is('&') && $this->peekChar->is('&') => $this->makeTwoCharTokenAndAdvance(TokenType::AND),

            $this->curChar->is('|') && $this->peekChar->is('|') => $this->makeTwoCharTokenAndAdvance(TokenType::OR),

            $this->curChar->is('+') && $this->peekChar->is('+') => $this->makeTwoCharTokenAndAdvance(TokenType::PLUS_PLUS),

            $this->curChar->is('-') && $this->peekChar->is('-') => $this->makeTwoCharTokenAndAdvance(TokenType::MINUS_MINUS),

            $this->curChar->is('"') => $this->makeTokenAndAdvance(TokenType::STRING, $this->readString()),

            $this->curChar->isLetter() => (function (): Token {
                $identifier = $this->readIdentifier();

                $type = TokenType::fromChar($identifier) ?? TokenType::IDENT;

                return Token::from($type, $identifier);
            })(),

            $this->curChar->isDigit() => (function (): Token {
                $number = $this->readNumber();

                $type = ctype_digit($number) ? TokenType::INT : TokenType::FLOAT;

                return Token::from($type, $number);
            })(),

            $this->curChar->is(self::EOF) => Token::from(TokenType::EOF, self::EOF),

            $this->curChar->isSingleChar() && TokenType::fromChar($this->curChar) instanceof TokenType => $this->makeTokenAndAdvance(
                TokenType::fromChar($this->curChar),
                $this->curChar->toString(),
            ),

            default => $this->makeTokenAndAdvance(TokenType::ILLEGAL, $this->curChar->toString()),
        };
    }

    public function readChar(): void
    {
        $isEof = $this->isEof();

        $this->prevChar = $this->curChar;
        $this->curChar = $isEof ? Char::from(self::EOF) : Char::from($this->input->char($this->readPosition));

        $this->position = $this->readPosition;
        $this->readPosition++;

        if (!$isEof) {
            $this->peekChar = Char::from($this->input->char($this->readPosition));
        }
    }

    public function skipWhitespaces(): void
    {
        while ($this->curChar->isWhitespace()) {
            $this->readChar();
        }
    }

    public function makeTwoCharTokenAndAdvance(TokenType $type): Token
    {
        $this->readChar();
        $token = Token::from($type, "{$this->prevChar}{$this->curChar}");
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

            if ($this->curChar->is('"') || $this->isEof()) {
                break;
            }
        }

        return $this->input->substr($position, $this->position - $position);
    }

    public function isEof(): bool
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
