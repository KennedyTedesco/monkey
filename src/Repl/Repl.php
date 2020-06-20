<?php

declare(strict_types=1);

namespace Monkey\Repl;

use Monkey\Evaluator\Environment;
use Monkey\Evaluator\Evaluator;
use Monkey\Lexer\Lexer;
use Monkey\Parser\Parser;
use Monkey\Parser\ProgramParser;

final class Repl
{
    public static function start(): void
    {
        \safe\fwrite(\STDOUT, <<<TEXT
               .="=.
             _/.-.-.\_     _
            ( ( o o ) )    ))
             |/  "  \|    //
              \'---'/    //
              /`"""`\\  ((
             / /_,_\ \\  \\
             \_\\_'__/ \  ))
             /`  /`~\  |//
            /   /    \  /
        ,--`,--'\/\    /
         '-- "--'  '--'
        
        # Monkey Programming Language #
        -------------------------------\n\n
        TEXT);

        $env = Environment::new();

        while (true) {
            $input = \readline("\n > ");
            if ('exit' === $input) {
                return;
            }

            $parser = new Parser(new Lexer($input));
            $program = (new ProgramParser())($parser);

            if (\count($parser->errors()) > 0) {
                self::printErrors($parser->errors());
                break;
            }

            $evaluated = (new Evaluator())->eval($program, $env);
            if (null !== $evaluated) {
                \safe\fwrite(\STDOUT, $evaluated->inspect().\PHP_EOL);
            }
        }
    }

    private static function printErrors(array $errors): void
    {
        foreach ($errors as $index => $error) {
            ++$index;
            \safe\fwrite(\STDOUT, "{$index}) {$error}");
        }
    }
}
