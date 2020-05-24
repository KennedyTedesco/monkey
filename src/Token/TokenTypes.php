<?php

declare(strict_types=1);

namespace Monkey\Token;

/**
 * @psalm-immutable
 */
final class TokenTypes
{
    public const T_ILLEGAL = 'ILLEGAL';
    public const T_EOF = 'EOF';

    public const T_IDENT = 'IDENT';
    public const T_INT = 'INT';

    public const T_ASSIGN = '=';
    public const T_PLUS = '+';
    public const T_MINUS = '-';
    public const T_BANG = '!';
    public const T_ASTERISK = '*';
    public const T_SLASH = '/';

    public const T_COMMA = ',';
    public const T_SEMICOLON = ';';

    public const T_LPAREN = '(';
    public const T_RPAREN = ')';
    public const T_LBRACE = '{';
    public const T_RBRACE = '}';

    public const T_LT = '<';
    public const T_LT_EQ = '<=';
    public const T_GT = '>';
    public const T_GT_EQ = '>=';
    public const T_EQ = '==';
    public const T_NOT_EQ = '!=';

    public const T_FUNC = 'FUNCTION';
    public const T_LET = 'LET';
    public const T_TRUE = 'TRUE';
    public const T_FALSE = 'FALSE';
    public const T_IF = 'IF';
    public const T_ELSE = 'ELSE';
    public const T_RETURN = 'RETURN';

    public static function lookupIdentifier(string $identifier): string
    {
        switch ($identifier) {
            case 'fn':
                return self::T_FUNC;
            case 'let':
                return self::T_LET;
            case 'true':
                return self::T_TRUE;
            case 'false':
                return self::T_FALSE;
            case 'if':
                return self::T_IF;
            case 'else':
                return self::T_ELSE;
            case 'return':
                return self::T_RETURN;
            default:
                return self::T_IDENT;
        }
    }

    public static function isSingleCharToken(string $ch): bool
    {
        switch ($ch) {
            case self::T_ASSIGN:
            case self::T_PLUS:
            case self::T_MINUS:
            case self::T_BANG:
            case self::T_ASTERISK:
            case self::T_SLASH:
            case self::T_COMMA:
            case self::T_SEMICOLON:
            case self::T_LT:
            case self::T_GT:
            case self::T_LPAREN:
            case self::T_RPAREN:
            case self::T_LBRACE:
            case self::T_RBRACE:
                return true;
            default:
                return false;
        }
    }
}
