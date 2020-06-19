<?php

declare(strict_types=1);

namespace Monkey\Token;

use ReflectionClass;

final class TokenType
{
    public const T_EOF = 0;
    public const T_ILLEGAL = -1;

    // Identifiers and literals
    public const T_IDENT = 0x100;
    public const T_INT = 0x101;
    public const T_STRING = 0x102;

    // Operators
    public const T_ASSIGN = 0x201;
    public const T_PLUS = 0x202;
    public const T_MINUS = 0x203;
    public const T_BANG = 0x204;
    public const T_ASTERISK = 0x205;
    public const T_SLASH = 0x206;

    // Delimiters
    public const T_COMMA = 0x207;
    public const T_SEMICOLON = 0x208;

    // Parentheses and braces
    public const T_LPAREN = 0x209;
    public const T_RPAREN = 0x20A;
    public const T_LBRACE = 0x20B;
    public const T_RBRACE = 0x20C;

    // Comparision operators
    public const T_LT = 0x20D;
    public const T_GT = 0x20E;
    public const T_EQ = 0x303;
    public const T_LT_EQ = 0x301;
    public const T_GT_EQ = 0x302;
    public const T_NOT_EQ = 0x304;

    // Keywords
    public const T_FN = 0x401;
    public const T_LET = 0x402;
    public const T_TRUE = 0x403;
    public const T_FALSE = 0x404;
    public const T_IF = 0x405;
    public const T_ELSE = 0x406;
    public const T_RETURN = 0x407;

    private const TOKEN_MAP = [
        // One char tokens
        '=' => self::T_ASSIGN,
        '+' => self::T_PLUS,
        '-' => self::T_MINUS,
        '!' => self::T_BANG,
        '*' => self::T_ASTERISK,
        '/' => self::T_SLASH,
        ',' => self::T_COMMA,
        ';' => self::T_SEMICOLON,
        '(' => self::T_LPAREN,
        ')' => self::T_RPAREN,
        '{' => self::T_LBRACE,
        '}' => self::T_RBRACE,
        '<' => self::T_LT,
        '>' => self::T_GT,
        // Two or more char tokens
        '>=' => self::T_GT_EQ,
        '<=' => self::T_LT_EQ,
        '==' => self::T_EQ,
        '!=' => self::T_NOT_EQ,
        'fn' => self::T_FN,
        'let' => self::T_LET,
        'true' => self::T_TRUE,
        'false' => self::T_FALSE,
        'if' => self::T_IF,
        'else' => self::T_ELSE,
        'return' => self::T_RETURN,
    ];

    public static function name(int $type): string
    {
        static $constants;
        $name = \array_search(
            $type, $constants ??= (new ReflectionClass(self::class))->getConstants(), true
        );

        return \is_string($name) ? $name : 'T_ILLEGAL';
    }

    public static function lookupToken(string $ch): ?int
    {
        return self::TOKEN_MAP[$ch] ?? null;
    }

    public static function isSingleCharToken(string $ch): bool
    {
        return 2 === (self::lookupToken($ch) ?? 0x0) >> 8;
    }
}
