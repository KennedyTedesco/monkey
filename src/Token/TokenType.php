<?php

declare(strict_types=1);

namespace Monkey\Token;

use ReflectionClass;

use function is_string;

final class TokenType
{
    /** @var int */
    public const T_EOF = 0;

    /** @var int */
    public const T_ILLEGAL = -1;

    // Identifiers and literals
    /** @var int */
    public const T_IDENT = 0x100;

    /** @var int */
    public const T_INT = 0x101;

    /** @var int */
    public const T_FLOAT = 0x102;

    /** @var int */
    public const T_STRING = 0x103;

    // Operators
    /** @var int */
    public const T_ASSIGN = 0x200;

    /** @var int */
    public const T_PLUS = 0x201;

    /** @var int */
    public const T_MINUS = 0x202;

    /** @var int */
    public const T_ASTERISK = 0x203;

    /** @var int */
    public const T_SLASH = 0x204;

    /** @var int */
    public const T_MODULO = 0x205;

    /** @var int */
    public const T_POWER = 0x206;

    /** @var int */
    public const T_PLUS_PLUS = 0x207;

    /** @var int */
    public const T_MINUS_MINUS = 0x208;

    // Logical operators
    /** @var int */
    public const T_NOT = 0x300;

    /** @var int */
    public const T_AND = 0x301;

    /** @var int */
    public const T_OR = 0x3002;

    // Delimiters
    /** @var int */
    public const T_COMMA = 0x400;

    /** @var int */
    public const T_SEMICOLON = 0x401;

    // Parentheses, braces and brackets
    /** @var int */
    public const T_LPAREN = 0x500;

    /** @var int */
    public const T_RPAREN = 0x501;

    /** @var int */
    public const T_LBRACE = 0x502;

    /** @var int */
    public const T_RBRACE = 0x503;

    /** @var int */
    public const T_LBRACKET = 0x504;

    /** @var int */
    public const T_RBRACKET = 0x505;

    // Comparision operators
    /** @var int */
    public const T_LT = 0x600;

    /** @var int */
    public const T_GT = 0x601;

    /** @var int */
    public const T_EQ = 0x602;

    /** @var int */
    public const T_LT_EQ = 0x603;

    /** @var int */
    public const T_GT_EQ = 0x604;

    /** @var int */
    public const T_NOT_EQ = 0x605;

    // Keywords
    /** @var int */
    public const T_FN = 0x700;

    /** @var int */
    public const T_LET = 0x701;

    /** @var int */
    public const T_TRUE = 0x702;

    /** @var int */
    public const T_FALSE = 0x703;

    /** @var int */
    public const T_IF = 0x704;

    /** @var int */
    public const T_ELSE = 0x705;

    /** @var int */
    public const T_RETURN = 0x706;

    /** @var int */
    public const T_WHILE = 0x707;

    /** @var array<string, int> */
    private const TOKEN_MAP = [
        '=' => self::T_ASSIGN,
        '+' => self::T_PLUS,
        '-' => self::T_MINUS,
        '!' => self::T_NOT,
        '*' => self::T_ASTERISK,
        '%' => self::T_MODULO,
        '**' => self::T_POWER,
        '/' => self::T_SLASH,
        '++' => self::T_PLUS_PLUS,
        '--' => self::T_MINUS_MINUS,

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

        '>=' => self::T_GT_EQ,
        '<=' => self::T_LT_EQ,
        '==' => self::T_EQ,
        '!=' => self::T_NOT_EQ,

        '&&' => self::T_AND,
        '||' => self::T_OR,

        'if' => self::T_IF,
        'fn' => self::T_FN,
        'let' => self::T_LET,
        'true' => self::T_TRUE,
        'else' => self::T_ELSE,
        'while' => self::T_WHILE,
        'false' => self::T_FALSE,
        'return' => self::T_RETURN,
    ];

    public static function lexeme(int $type): string
    {
        static $constants;
        $name = array_search(
            $type,
            $constants ??= (new ReflectionClass(self::class))->getConstants(),
            true,
        );

        return is_string($name) ? $name : 'T_ILLEGAL';
    }

    public static function lookupToken(string $ch): ?int
    {
        return self::TOKEN_MAP[$ch] ?? null;
    }

    public static function isSingleCharToken(string $ch): bool
    {
        return mb_strlen($ch) === 1 && self::lookupToken($ch);
    }
}
