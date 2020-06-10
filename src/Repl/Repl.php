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

        while (true) {
            /** @var string $input */
            $input = \fgets(\STDIN);
            if (':q' === \trim($input)) {
                return;
            }

            \safe\fwrite(\STDOUT, \PHP_EOL);

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
