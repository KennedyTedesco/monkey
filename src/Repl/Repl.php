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
        \fwrite(\STDOUT, <<<TEXT
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

        $env = new Environment();
        while (true) {
            $input = \readline("\n > ");
            if ('exit' === $input) {
                return;
            }

            self::eval($input, $env);
        }
    }

    public static function eval(string $input, Environment $env): void
    {
        $parser = new Parser(new Lexer($input));

        if (\count($parser->errors()) > 0) {
            self::printErrors($parser->errors());
            return;
        }

        $evaluated = (new Evaluator())->eval(
            (new ProgramParser())($parser), $env
        );

        if (null !== $evaluated) {
            \fwrite(\STDOUT, $evaluated->inspect().\PHP_EOL);
        }
    }

    private static function printErrors(array $errors): void
    {
        foreach ($errors as $index => $error) {
            ++$index;
            \fwrite(\STDOUT, "{$index}) {$error}");
        }
    }
}
