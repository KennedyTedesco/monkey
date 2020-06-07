<?php

declare(strict_types=1);

namespace Tests;

use Monkey\Lexer\Lexer;
use Monkey\Token\TokenType;

test('basic tokens', function () {
    $input = <<<'MONKEY'
		let five = 5;
		let ten = 10;

		let add = fn(x, y) {
		  x + y;
		};

		let result = add(five, ten);
		!-/*5;
		5 < 10 > 5;

		if (5 < 10) {
			return true;
		} else {
			return false;
		}

        10 == 10;
        10 != 9;

        10 >= 2;
        10 <= 2;
MONKEY;

    $tokens = [
        // let five = 5;
        [TokenType::T_LET, 'let'],
        [TokenType::T_IDENT, 'five'],
        [TokenType::T_ASSIGN, '='],
        [TokenType::T_INT, '5'],
        [TokenType::T_SEMICOLON, ';'],

        // let ten = 10;
        [TokenType::T_LET, 'let'],
        [TokenType::T_IDENT, 'ten'],
        [TokenType::T_ASSIGN, '='],
        [TokenType::T_INT, '10'],
        [TokenType::T_SEMICOLON, ';'],

        // let add = fn(x, y) {
        //    x + y;
        // };
        [TokenType::T_LET, 'let'],
        [TokenType::T_IDENT, 'add'],
        [TokenType::T_ASSIGN, '='],
        [TokenType::T_FUNC, 'fn'],
        [TokenType::T_LPAREN, '('],
        [TokenType::T_IDENT, 'x'],
        [TokenType::T_COMMA, ','],
        [TokenType::T_IDENT, 'y'],
        [TokenType::T_RPAREN, ')'],
        [TokenType::T_LBRACE, '{'],
        [TokenType::T_IDENT, 'x'],
        [TokenType::T_PLUS, '+'],
        [TokenType::T_IDENT, 'y'],
        [TokenType::T_SEMICOLON, ';'],
        [TokenType::T_RBRACE, '}'],
        [TokenType::T_SEMICOLON, ';'],

        // let result = add(five, ten);
        [TokenType::T_LET, 'let'],
        [TokenType::T_IDENT, 'result'],
        [TokenType::T_ASSIGN, '='],
        [TokenType::T_IDENT, 'add'],
        [TokenType::T_LPAREN, '('],
        [TokenType::T_IDENT, 'five'],
        [TokenType::T_COMMA, ','],
        [TokenType::T_IDENT, 'ten'],
        [TokenType::T_RPAREN, ')'],
        [TokenType::T_SEMICOLON, ';'],

        // !-/*5;
        [TokenType::T_BANG, '!'],
        [TokenType::T_MINUS, '-'],
        [TokenType::T_SLASH, '/'],
        [TokenType::T_ASTERISK, '*'],
        [TokenType::T_INT, '5'],
        [TokenType::T_SEMICOLON, ';'],

        // 5 < 10 > 5;
        [TokenType::T_INT, '5'],
        [TokenType::T_LT, '<'],
        [TokenType::T_INT, '10'],
        [TokenType::T_GT, '>'],
        [TokenType::T_INT, '5'],
        [TokenType::T_SEMICOLON, ';'],

        // if (5 < 10) {
        // 	 return true;
        // } else {
        //	 return false;
        // }
        [TokenType::T_IF, 'if'],
        [TokenType::T_LPAREN, '('],
        [TokenType::T_INT, '5'],
        [TokenType::T_LT, '<'],
        [TokenType::T_INT, '10'],
        [TokenType::T_RPAREN, ')'],
        [TokenType::T_LBRACE, '{'],
        [TokenType::T_RETURN, 'return'],
        [TokenType::T_TRUE, 'true'],
        [TokenType::T_SEMICOLON, ';'],
        [TokenType::T_RBRACE, '}'],
        [TokenType::T_ELSE, 'else'],
        [TokenType::T_LBRACE, '{'],
        [TokenType::T_RETURN, 'return'],
        [TokenType::T_FALSE, 'false'],
        [TokenType::T_SEMICOLON, ';'],
        [TokenType::T_RBRACE, '}'],

        // 10 == 10;
        // 10 != 9;
        [TokenType::T_INT, '10'],
        [TokenType::T_EQ, '=='],
        [TokenType::T_INT, '10'],
        [TokenType::T_SEMICOLON, ';'],
        [TokenType::T_INT, '10'],
        [TokenType::T_NOT_EQ, '!='],
        [TokenType::T_INT, '9'],
        [TokenType::T_SEMICOLON, ';'],

        // 10 >= 2;
        // 10 <= 2;
        [TokenType::T_INT, '10'],
        [TokenType::T_GT_EQ, '>='],
        [TokenType::T_INT, '2'],
        [TokenType::T_SEMICOLON, ';'],
        [TokenType::T_INT, '10'],
        [TokenType::T_LT_EQ, '<='],
        [TokenType::T_INT, '2'],
        [TokenType::T_SEMICOLON, ';'],

        [TokenType::T_EOF, Lexer::EOF],
    ];

    $lexer = new Lexer($input);

    foreach ($tokens as $tt) {
        [$type, $literal] = $tt;

        $token = $lexer->nextToken();

        assertSame($type, $token->type());
        assertSame($literal, $token->literal());
    }
});

test('comparision operators', function () {
    $input = '5 > 4 == 3 < 4 <= 3;';

    $tokens = [
        [TokenType::T_INT, '5'],
        [TokenType::T_GT, '>'],
        [TokenType::T_INT, '4'],
        [TokenType::T_EQ, '=='],
        [TokenType::T_INT, '3'],
        [TokenType::T_LT, '<'],
        [TokenType::T_INT, '4'],
        [TokenType::T_LT_EQ, '<='],
        [TokenType::T_INT, '3'],
        [TokenType::T_SEMICOLON, ';'],
    ];

    $lexer = new Lexer($input);

    foreach ($tokens as $tt) {
        [$type, $literal] = $tt;

        $token = $lexer->nextToken();

        assertSame($type, $token->type());
        assertSame($literal, $token->literal());
    }
});
