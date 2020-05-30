<?php

declare(strict_types=1);

namespace Tests;

use Monkey\Ast\Identifier;
use Monkey\Ast\LetStatement;
use Monkey\Ast\Program;
use Monkey\Token\Token;
use Monkey\Token\TokenType;

test('toString', function () {
    $program = new Program();
    $program->addStatement(
        new LetStatement(
            new Token(TokenType::T_LET, 'let'),
            new Identifier(new Token(TokenType::T_IDENT, 'foo'), '10')
        )
    );

    assertSame('let foo = 10;', $program->toString());
});
