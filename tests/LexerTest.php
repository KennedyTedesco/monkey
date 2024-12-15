<?php

declare(strict_types=1);

namespace Tests;

use MonkeyLang\Lang\Lexer\Lexer;
use MonkeyLang\Lang\Token\TokenType;

test('basic tokens', function () {
    $input = <<<MONKEY
        let foo1 = 10;
        let foo_bar = 10;
        let _bar = 10;
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
        "foobar";
        "foo bar";
        [1, 2];
        true && false;
        true || false;
        10 % 2;
        10.5 * 0.5;
        2 ** 2;

        while (true) {
            1
        }

        a++;
        a--;
MONKEY;

    $tokens = [
        // let foo1 = 10;
        [TokenType::LET, 'let'],
        [TokenType::IDENT, 'foo1'],
        [TokenType::ASSIGN, '='],
        [TokenType::INT, '10'],
        [TokenType::SEMICOLON, ';'],

        // let foo_bar = 10;
        [TokenType::LET, 'let'],
        [TokenType::IDENT, 'foo_bar'],
        [TokenType::ASSIGN, '='],
        [TokenType::INT, '10'],
        [TokenType::SEMICOLON, ';'],

        // let _bar = 10;
        [TokenType::LET, 'let'],
        [TokenType::IDENT, '_bar'],
        [TokenType::ASSIGN, '='],
        [TokenType::INT, '10'],
        [TokenType::SEMICOLON, ';'],

        // let five = 5;
        [TokenType::LET, 'let'],
        [TokenType::IDENT, 'five'],
        [TokenType::ASSIGN, '='],
        [TokenType::INT, '5'],
        [TokenType::SEMICOLON, ';'],

        // let ten = 10;
        [TokenType::LET, 'let'],
        [TokenType::IDENT, 'ten'],
        [TokenType::ASSIGN, '='],
        [TokenType::INT, '10'],
        [TokenType::SEMICOLON, ';'],

        // let add = fn(x, y) {
        //    x + y;
        // };
        [TokenType::LET, 'let'],
        [TokenType::IDENT, 'add'],
        [TokenType::ASSIGN, '='],
        [TokenType::FN, 'fn'],
        [TokenType::LPAREN, '('],
        [TokenType::IDENT, 'x'],
        [TokenType::COMMA, ','],
        [TokenType::IDENT, 'y'],
        [TokenType::RPAREN, ')'],
        [TokenType::LBRACE, '{'],
        [TokenType::IDENT, 'x'],
        [TokenType::PLUS, '+'],
        [TokenType::IDENT, 'y'],
        [TokenType::SEMICOLON, ';'],
        [TokenType::RBRACE, '}'],
        [TokenType::SEMICOLON, ';'],

        // let result = add(five, ten);
        [TokenType::LET, 'let'],
        [TokenType::IDENT, 'result'],
        [TokenType::ASSIGN, '='],
        [TokenType::IDENT, 'add'],
        [TokenType::LPAREN, '('],
        [TokenType::IDENT, 'five'],
        [TokenType::COMMA, ','],
        [TokenType::IDENT, 'ten'],
        [TokenType::RPAREN, ')'],
        [TokenType::SEMICOLON, ';'],

        // !-/*5;
        [TokenType::NOT, '!'],
        [TokenType::MINUS, '-'],
        [TokenType::SLASH, '/'],
        [TokenType::ASTERISK, '*'],
        [TokenType::INT, '5'],
        [TokenType::SEMICOLON, ';'],

        // 5 < 10 > 5;
        [TokenType::INT, '5'],
        [TokenType::LT, '<'],
        [TokenType::INT, '10'],
        [TokenType::GT, '>'],
        [TokenType::INT, '5'],
        [TokenType::SEMICOLON, ';'],

        // if (5 < 10) {
        // 	 return true;
        // } else {
        //	 return false;
        // }
        [TokenType::IF, 'if'],
        [TokenType::LPAREN, '('],
        [TokenType::INT, '5'],
        [TokenType::LT, '<'],
        [TokenType::INT, '10'],
        [TokenType::RPAREN, ')'],
        [TokenType::LBRACE, '{'],
        [TokenType::RETURN, 'return'],
        [TokenType::TRUE, 'true'],
        [TokenType::SEMICOLON, ';'],
        [TokenType::RBRACE, '}'],
        [TokenType::ELSE, 'else'],
        [TokenType::LBRACE, '{'],
        [TokenType::RETURN, 'return'],
        [TokenType::FALSE, 'false'],
        [TokenType::SEMICOLON, ';'],
        [TokenType::RBRACE, '}'],

        // 10 == 10;
        // 10 != 9;
        [TokenType::INT, '10'],
        [TokenType::EQ, '=='],
        [TokenType::INT, '10'],
        [TokenType::SEMICOLON, ';'],
        [TokenType::INT, '10'],
        [TokenType::NOT_EQ, '!='],
        [TokenType::INT, '9'],
        [TokenType::SEMICOLON, ';'],

        // 10 >= 2;
        // 10 <= 2;
        [TokenType::INT, '10'],
        [TokenType::GT_EQ, '>='],
        [TokenType::INT, '2'],
        [TokenType::SEMICOLON, ';'],
        [TokenType::INT, '10'],
        [TokenType::LT_EQ, '<='],
        [TokenType::INT, '2'],
        [TokenType::SEMICOLON, ';'],

        // "foobar";
        // "foo bar";
        [TokenType::STRING, 'foobar'],
        [TokenType::SEMICOLON, ';'],
        [TokenType::STRING, 'foo bar'],
        [TokenType::SEMICOLON, ';'],

        // [1, 2];
        [TokenType::LBRACKET, '['],
        [TokenType::INT, '1'],
        [TokenType::COMMA, ','],
        [TokenType::INT, '2'],
        [TokenType::RBRACKET, ']'],
        [TokenType::SEMICOLON, ';'],

        // true && false;
        [TokenType::TRUE, 'true'],
        [TokenType::AND, '&&'],
        [TokenType::FALSE, 'false'],
        [TokenType::SEMICOLON, ';'],

        // true || false;
        [TokenType::TRUE, 'true'],
        [TokenType::OR, '||'],
        [TokenType::FALSE, 'false'],
        [TokenType::SEMICOLON, ';'],

        // 10 % 2;
        [TokenType::INT, '10'],
        [TokenType::MODULO, '%'],
        [TokenType::INT, '2'],
        [TokenType::SEMICOLON, ';'],

        // 10.5 * 0.5;
        [TokenType::FLOAT, '10.5'],
        [TokenType::ASTERISK, '*'],
        [TokenType::FLOAT, '0.5'],
        [TokenType::SEMICOLON, ';'],

        // 2 ** 2;
        [TokenType::INT, '2'],
        [TokenType::POWER, '**'],
        [TokenType::INT, '2'],
        [TokenType::SEMICOLON, ';'],

        // while (true) {
        //     1
        // }
        [TokenType::WHILE, 'while'],
        [TokenType::LPAREN, '('],
        [TokenType::TRUE, 'true'],
        [TokenType::RPAREN, ')'],
        [TokenType::LBRACE, '{'],
        [TokenType::INT, '1'],
        [TokenType::RBRACE, '}'],

        // a++;
        [TokenType::IDENT, 'a'],
        [TokenType::PLUS_PLUS, '++'],
        [TokenType::SEMICOLON, ';'],

        // a--;
        [TokenType::IDENT, 'a'],
        [TokenType::MINUS_MINUS, '--'],
        [TokenType::SEMICOLON, ';'],

        [TokenType::EOF, Lexer::EOF],
    ];

    $lexer = new Lexer($input);

    foreach ($tokens as $tt) {
        [$type, $literal] = $tt;

        $token = $lexer->nextToken();

        expect($token->type())->toBe($type);
        expect($token->literal())->toBe($literal);
    }
});

test('comparision operators', function () {
    $input = '5 > 4 == 3 < 4 <= 3;';

    $tokens = [
        [TokenType::INT, '5'],
        [TokenType::GT, '>'],
        [TokenType::INT, '4'],
        [TokenType::EQ, '=='],
        [TokenType::INT, '3'],
        [TokenType::LT, '<'],
        [TokenType::INT, '4'],
        [TokenType::LT_EQ, '<='],
        [TokenType::INT, '3'],
        [TokenType::SEMICOLON, ';'],
    ];

    $lexer = new Lexer($input);

    foreach ($tokens as $tt) {
        [$type, $literal] = $tt;

        $token = $lexer->nextToken();

        expect($token->type())->toBe($type);
        expect($token->literal())->toBe($literal);
    }
});
