<?php

declare(strict_types=1);

namespace Tests;

use MonkeyLang\Ast\Expressions\IdentifierExpression;
use MonkeyLang\Ast\Program;
use MonkeyLang\Ast\Statements\LetStatement;
use MonkeyLang\Ast\Types\IntegerLiteral;
use MonkeyLang\Token\Token;
use MonkeyLang\Token\TokenType;

test('to string', function () {
    $program = new Program();

    $program->addStatement(
        new LetStatement(
            new Token(TokenType::LET, 'let'),
            new IdentifierExpression(new Token(TokenType::IDENT, 'foo'), 'foo'),
            new IntegerLiteral(new Token(TokenType::INT, '10'), 10),
        ),
    );

    expect('let foo = 10;')->toBe($program->toString());
});
