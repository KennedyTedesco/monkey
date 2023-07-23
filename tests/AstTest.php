<?php

declare(strict_types=1);

namespace Tests;

use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Ast\Program;
use Monkey\Ast\Statements\LetStatement;
use Monkey\Ast\Types\IntegerLiteral;
use Monkey\Token\Token;
use Monkey\Token\TokenType;

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
