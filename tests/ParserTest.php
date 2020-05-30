<?php

declare(strict_types=1);

namespace Tests;

use Monkey\Ast\LetStatement;
use Monkey\Ast\ReturnStatement;
use Monkey\Lexer\Lexer;
use Monkey\Parser\Parser;
use Monkey\Parser\ProgramParser;

test('let parser', function () {
    $input = <<<'MONKEY'
        let x = 5;
        let y = 10;
        let foo_bar = 838383;
MONKEY;

    $lexer = new Lexer($input);
    $parser = new Parser($lexer);
    $program = (new ProgramParser())($parser);

    assertSame(3, $program->count());
    assertCount(0, $parser->errors());

    $identifiers = ['x', 'y', 'foo_bar'];

    /**
     * @var int          $i
     * @var LetStatement $stmt
     */
    foreach ($program->statements() as $i => $stmt) {
        assertInstanceOf(LetStatement::class, $stmt);
        assertSame('let', $stmt->tokenLiteral());
        assertSame($identifiers[$i], $stmt->identifierName());
    }
});

test('return parser', function () {
    $input = <<<'MONKEY'
    return 10;
    return 100;
    return 1000;
MONKEY;

    $lexer = new Lexer($input);
    $parser = new Parser($lexer);
    $program = (new ProgramParser())($parser);

    assertSame(3, $program->count());
    assertCount(0, $parser->errors());

    /**
     * @var int          $i
     * @var LetStatement $stmt
     */
    foreach ($program->statements() as $stmt) {
        assertInstanceOf(ReturnStatement::class, $stmt);
        assertSame('return', $stmt->tokenLiteral());
    }
});
