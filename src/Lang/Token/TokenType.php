<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Token;

use MonkeyLang\Lang\Lexer\Char;
use MonkeyLang\Lang\Parser\Precedence;

enum TokenType: int
{
    // Special tokens
    case EOF = 0;
    case ILLEGAL = -1;

    // Identifiers and literals
    case IDENT = 0x100;
    case INT = 0x101;
    case FLOAT = 0x102;
    case STRING = 0x103;

    // Operators
    case ASSIGN = 0x200;
    case PLUS = 0x201;
    case MINUS = 0x202;
    case ASTERISK = 0x203;
    case SLASH = 0x204;
    case MODULO = 0x205;
    case POWER = 0x206;
    case PLUS_PLUS = 0x207;
    case MINUS_MINUS = 0x208;

    // Logical operators
    case NOT = 0x300;
    case AND = 0x301;
    case OR = 0x302;

    // Delimiters
    case COMMA = 0x400;
    case SEMICOLON = 0x401;

    // Parentheses, braces, and brackets
    case LPAREN = 0x500;
    case RPAREN = 0x501;
    case LBRACE = 0x502;
    case RBRACE = 0x503;
    case LBRACKET = 0x504;
    case RBRACKET = 0x505;

    // Comparison operators
    case LT = 0x600;
    case GT = 0x601;
    case EQ = 0x602;
    case LT_EQ = 0x603;
    case GT_EQ = 0x604;
    case NOT_EQ = 0x605;

    // Keywords
    case FN = 0x700;
    case LET = 0x701;
    case TRUE = 0x702;
    case FALSE = 0x703;
    case IF = 0x704;
    case ELSE = 0x705;
    case RETURN = 0x706;
    case WHILE = 0x707;

    public static function fromChar(string | Char $char): ?self
    {
        return match ((string)$char) {
            '=' => self::ASSIGN,
            '+' => self::PLUS,
            '-' => self::MINUS,
            '!' => self::NOT,
            '*' => self::ASTERISK,
            '%' => self::MODULO,
            '**' => self::POWER,
            '/' => self::SLASH,
            '++' => self::PLUS_PLUS,
            '--' => self::MINUS_MINUS,

            ',' => self::COMMA,
            ';' => self::SEMICOLON,

            '(' => self::LPAREN,
            ')' => self::RPAREN,
            '{' => self::LBRACE,
            '}' => self::RBRACE,
            '<' => self::LT,
            '>' => self::GT,
            '[' => self::LBRACKET,
            ']' => self::RBRACKET,

            '>=' => self::GT_EQ,
            '<=' => self::LT_EQ,
            '==' => self::EQ,
            '!=' => self::NOT_EQ,

            '&&' => self::AND,
            '||' => self::OR,

            'if' => self::IF,
            'fn' => self::FN,
            'let' => self::LET,
            'true' => self::TRUE,
            'else' => self::ELSE,
            'while' => self::WHILE,
            'false' => self::FALSE,
            'return' => self::RETURN,

            default => null,
        };
    }

    public function precedence(): Precedence
    {
        return match ($this) {
            self::EQ, self::NOT_EQ => Precedence::EQUALS,
            self::LT, self::LT_EQ, self::GT, self::GT_EQ => Precedence::LESS_GREATER,
            self::PLUS, self::MINUS => Precedence::SUM,
            self::SLASH, self::ASTERISK, self::MODULO => Precedence::PRODUCT,
            self::LPAREN => Precedence::CALL,
            self::LBRACKET => Precedence::INDEX,
            self::AND => Precedence::AND,
            self::OR => Precedence::OR,
            self::POWER => Precedence::POWER,
            default => Precedence::LOWEST,
        };
    }

    public function lexeme(): string
    {
        return match ($this) {
            self::ASSIGN => '=',
            self::PLUS => '+',
            self::MINUS => '-',
            self::NOT => '!',
            self::ASTERISK => '*',
            self::MODULO => '%',
            self::POWER => '**',
            self::SLASH => '/',
            self::PLUS_PLUS => '++',
            self::MINUS_MINUS => '--',

            self::COMMA => ',',
            self::SEMICOLON => ';',

            self::LPAREN => '(',
            self::RPAREN => ')',
            self::LBRACE => '{',
            self::RBRACE => '}',
            self::LT => '<',
            self::GT => '>',
            self::LBRACKET => '[',
            self::RBRACKET => ']',

            self::GT_EQ => '>=',
            self::LT_EQ => '<=',
            self::EQ => '==',
            self::NOT_EQ => '!=',

            self::AND => '&&',
            self::OR => '||',

            default => 'T_ILLEGAL',
        };
    }
}
