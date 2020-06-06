<?php

declare(strict_types=1);

namespace Monkey\Repl;

use Monkey\Lexer\Lexer;
use Monkey\Token\TokenType;

final class Repl
{
    public function start(): void
    {
        $welcome = <<<TEXT
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

@@@@@@@@@ Monkey Programming Language @@@@@@@@@
-----------------------------------------------
TEXT;

        \fwrite(\STDOUT, "{$welcome}\n");

        while (true) {
            $input = \trim(\fgets(\STDIN));
            if (':q' === $input) {
                return;
            }

            \fwrite(\STDOUT, \PHP_EOL);

            $lexer = new Lexer($input);
            while (!$lexer->isEnd()) {
                $token = $lexer->nextToken();
                $name = TokenType::name($token->type);
                \fwrite(\STDOUT, "{$name}: {$token->literal}\n");
            }
        }
    }
}
