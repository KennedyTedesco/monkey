<?php

declare(strict_types=1);

namespace Tests;

use MonkeyLang\Lang\Ast\Expressions\IdentifierExpression;
use MonkeyLang\Lang\Ast\Program;
use MonkeyLang\Lang\Ast\Statements\LetStatement;
use MonkeyLang\Lang\Ast\Types\IntegerLiteral;
use MonkeyLang\Lang\Token\Token;
use MonkeyLang\Lang\Token\TokenType;

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
