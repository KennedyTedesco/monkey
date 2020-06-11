<?php

declare(strict_types=1);

namespace Monkey\Repl;

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
        
        @ Monkey Programming Language @
        --------------------------------\n\n
        TEXT);

        \safe\fwrite(\STDOUT, '> ');

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

            \safe\fwrite(\STDOUT, $program->toString());
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
