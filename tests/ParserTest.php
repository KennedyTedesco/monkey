<?php

declare(strict_types=1);

namespace Tests;

use Monkey\Ast\LetStatement;
use Monkey\Lexer\Lexer;
use Monkey\Parser\Parser;
use Monkey\Parser\ProgramParser;

test('next token', function () {
    $input = <<<'MONKEY'
        let x = 5;
        let y = 10;
        let foo_bar = 838383;
MONKEY;

    $lexer = new Lexer($input);
    $parser = new Parser($lexer);
    $program = (new ProgramParser())($parser);

    assertSame(3, $program->count());

    $identifiers = ['x', 'y', 'foo_bar'];

    for ($i = 0; $i < $program->count(); ++$i) {
        /** @var LetStatement $stmt */
        $stmt = $program->statement($i);

        assertInstanceOf(LetStatement::class, $stmt);
        assertSame('let', $stmt->tokenLiteral());
        assertSame($identifiers[$i], $stmt->identifierLiteral());
    }
});
