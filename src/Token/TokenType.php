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
    public const T_ASTERISK = 0x205;
    public const T_SLASH = 0x206;
    public const T_MODULO = 0x207;

    // Logical operators
    public const T_NOT = 0x241;
    public const T_AND = 0x305;
    public const T_OR = 0x306;

    // Delimiters
    public const T_COMMA = 0x210;
    public const T_SEMICOLON = 0x211;

    // Parentheses, braces and brackets
    public const T_LPAREN = 0x221;
    public const T_RPAREN = 0x222;
    public const T_LBRACE = 0x223;
    public const T_RBRACE = 0x224;
    public const T_LBRACKET = 0x225;
    public const T_RBRACKET = 0x226;

    // Comparision operators
    public const T_LT = 0x231;
    public const T_GT = 0x232;
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
        '!' => self::T_NOT,
        '*' => self::T_ASTERISK,
        '%' => self::T_MODULO,
        '/' => self::T_SLASH,
        ',' => self::T_COMMA,
        ';' => self::T_SEMICOLON,
        '(' => self::T_LPAREN,
        ')' => self::T_RPAREN,
        '{' => self::T_LBRACE,
        '}' => self::T_RBRACE,
        '<' => self::T_LT,
        '>' => self::T_GT,
        '[' => self::T_LBRACKET,
        ']' => self::T_RBRACKET,
        // Two or more char tokens
        '>=' => self::T_GT_EQ,
        '<=' => self::T_LT_EQ,
        '==' => self::T_EQ,
        '!=' => self::T_NOT_EQ,
        '&&' => self::T_AND,
        '||' => self::T_OR,
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
