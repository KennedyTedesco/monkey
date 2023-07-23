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
            $this->currentAndPeekCharIs('*', '*') => $this->makeTwoCharTokenAndAdvance(TokenType::POWER),

            $this->currentAndPeekCharIs('=', '=') => $this->makeTwoCharTokenAndAdvance(TokenType::EQ),

            $this->currentAndPeekCharIs('!', '=') => $this->makeTwoCharTokenAndAdvance(TokenType::NOT_EQ),

            $this->currentAndPeekCharIs('>', '=') => $this->makeTwoCharTokenAndAdvance(TokenType::GT_EQ),

            $this->currentAndPeekCharIs('<', '=') => $this->makeTwoCharTokenAndAdvance(TokenType::LT_EQ),

            $this->currentAndPeekCharIs('&', '&') => $this->makeTwoCharTokenAndAdvance(TokenType::AND),

            $this->currentAndPeekCharIs('|', '|') => $this->makeTwoCharTokenAndAdvance(TokenType::OR),

            $this->currentAndPeekCharIs('+', '+') => $this->makeTwoCharTokenAndAdvance(TokenType::PLUS_PLUS),

            $this->currentAndPeekCharIs('-', '-') => $this->makeTwoCharTokenAndAdvance(TokenType::MINUS_MINUS),

            $this->curChar->is('"') => $this->makeTokenAndAdvance(TokenType::STRING, $this->readString()),

            $this->curChar->isLetter() => Token::from(
                TokenType::fromChar($identifier = $this->readIdentifier()) ?? TokenType::IDENT,
                $identifier,
            ),

            $this->curChar->isDigit() => Token::from(ctype_digit($number = $this->readNumber()) ? TokenType::INT : TokenType::FLOAT, $number),

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

    public function currentAndPeekCharIs(
        string $firstOperator,
        string $secondOperator,
    ): bool {
        return $this->curChar->is($firstOperator) && $this->peekChar->is($secondOperator);
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
