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
    public function start(): void
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

        \safe\fwrite(\STDOUT, '> ');

        $env = new Environment();

        while (true) {
            /** @var string $input */
            $input = \trim(\fgets(\STDIN));
            if ('exit' === $input) {
                return;
            }

            if ('' === $input) {
                \safe\fwrite(\STDOUT, '> ');
            }

            $parser = new Parser(new Lexer($input));
            $program = (new ProgramParser())($parser);

            if (\count($parser->errors()) > 0) {
                $this->printErrors($parser->errors());
                break;
            }

            $evaluated = (new Evaluator($env))->eval($program);
            if (null !== $evaluated) {
                \safe\fwrite(\STDOUT, $evaluated->inspect());
            }
        }
    }

    private function printErrors(array $errors): void
    {
        foreach ($errors as $index => $error) {
            ++$index;
            \safe\fwrite(\STDOUT, "{$index}) {$error}");
        }
    }
}
