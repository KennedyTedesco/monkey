<?php

declare(strict_types=1);

namespace Tests;

use Monkey\Lexer\Lexer;
use Monkey\Token\TokenTypes;

test('test next token', function () {
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
        [TokenTypes::T_LET, 'let'],
        [TokenTypes::T_IDENT, 'five'],
        [TokenTypes::T_ASSIGN, '='],
        [TokenTypes::T_INT, '5'],
        [TokenTypes::T_SEMICOLON, ';'],

        // let ten = 10;
        [TokenTypes::T_LET, 'let'],
        [TokenTypes::T_IDENT, 'ten'],
        [TokenTypes::T_ASSIGN, '='],
        [TokenTypes::T_INT, '10'],
        [TokenTypes::T_SEMICOLON, ';'],

        // let add = fn(x, y) {
        //    x + y;
        // };
        [TokenTypes::T_LET, 'let'],
        [TokenTypes::T_IDENT, 'add'],
        [TokenTypes::T_ASSIGN, '='],
        [TokenTypes::T_FUNC, 'fn'],
        [TokenTypes::T_LPAREN, '('],
        [TokenTypes::T_IDENT, 'x'],
        [TokenTypes::T_COMMA, ','],
        [TokenTypes::T_IDENT, 'y'],
        [TokenTypes::T_RPAREN, ')'],
        [TokenTypes::T_LBRACE, '{'],
        [TokenTypes::T_IDENT, 'x'],
        [TokenTypes::T_PLUS, '+'],
        [TokenTypes::T_IDENT, 'y'],
        [TokenTypes::T_SEMICOLON, ';'],
        [TokenTypes::T_RBRACE, '}'],
        [TokenTypes::T_SEMICOLON, ';'],

        // let result = add(five, ten);
        [TokenTypes::T_LET, 'let'],
        [TokenTypes::T_IDENT, 'result'],
        [TokenTypes::T_ASSIGN, '='],
        [TokenTypes::T_IDENT, 'add'],
        [TokenTypes::T_LPAREN, '('],
        [TokenTypes::T_IDENT, 'five'],
        [TokenTypes::T_COMMA, ','],
        [TokenTypes::T_IDENT, 'ten'],
        [TokenTypes::T_RPAREN, ')'],
        [TokenTypes::T_SEMICOLON, ';'],

        // !-/*5;
        [TokenTypes::T_BANG, '!'],
        [TokenTypes::T_MINUS, '-'],
        [TokenTypes::T_SLASH, '/'],
        [TokenTypes::T_ASTERISK, '*'],
        [TokenTypes::T_INT, '5'],
        [TokenTypes::T_SEMICOLON, ';'],

        // 5 < 10 > 5;
        [TokenTypes::T_INT, '5'],
        [TokenTypes::T_LT, '<'],
        [TokenTypes::T_INT, '10'],
        [TokenTypes::T_GT, '>'],
        [TokenTypes::T_INT, '5'],
        [TokenTypes::T_SEMICOLON, ';'],

        // if (5 < 10) {
        // 	 return true;
        // } else {
        //	 return false;
        // }
        [TokenTypes::T_IF, 'if'],
        [TokenTypes::T_LPAREN, '('],
        [TokenTypes::T_INT, '5'],
        [TokenTypes::T_LT, '<'],
        [TokenTypes::T_INT, '10'],
        [TokenTypes::T_RPAREN, ')'],
        [TokenTypes::T_LBRACE, '{'],
        [TokenTypes::T_RETURN, 'return'],
        [TokenTypes::T_TRUE, 'true'],
        [TokenTypes::T_SEMICOLON, ';'],
        [TokenTypes::T_RBRACE, '}'],
        [TokenTypes::T_ELSE, 'else'],
        [TokenTypes::T_LBRACE, '{'],
        [TokenTypes::T_RETURN, 'return'],
        [TokenTypes::T_FALSE, 'false'],
        [TokenTypes::T_SEMICOLON, ';'],
        [TokenTypes::T_RBRACE, '}'],

        // 10 == 10;
        // 10 != 9;
        [TokenTypes::T_INT, '10'],
        [TokenTypes::T_EQ, '=='],
        [TokenTypes::T_INT, '10'],
        [TokenTypes::T_SEMICOLON, ';'],
        [TokenTypes::T_INT, '10'],
        [TokenTypes::T_NOT_EQ, '!='],
        [TokenTypes::T_INT, '9'],
        [TokenTypes::T_SEMICOLON, ';'],

        // 10 >= 2;
        // 10 <= 2;
        [TokenTypes::T_INT, '10'],
        [TokenTypes::T_GT_EQ, '>='],
        [TokenTypes::T_INT, '2'],
        [TokenTypes::T_SEMICOLON, ';'],
        [TokenTypes::T_INT, '10'],
        [TokenTypes::T_LT_EQ, '<='],
        [TokenTypes::T_INT, '2'],
        [TokenTypes::T_SEMICOLON, ';'],

        [TokenTypes::T_EOF, ''],
    ];

    $lexer = new Lexer($input);

    foreach ($tokens as $tt) {
        [$type, $literal] = $tt;

        $token = $lexer->nextToken();

        assertSame($type, $token->type());
        assertSame($literal, $token->literal());
    }
});
