<?php

declare(strict_types=1);

namespace Monkey\Repl;

use Monkey\Lexer\Lexer;
use Monkey\Token\TokenType;

final class Repl
{
    public function start(): void
    {
        \fwrite(\STDOUT, "----------------------------------------------\n");
        \fwrite(\STDOUT, "| Welcome to the Monkey Programming Language |\n");
        \fwrite(\STDOUT, "----------------------------------------------\n");

        while (true) {
            $input = \trim(\fgets(\STDIN));
            if (':q' === $input) {
                return;
            }

            $lexer = new Lexer($input);
            while (!$lexer->isEnd()) {
                $token = $lexer->nextToken();
                //$name = TokenType::tokenName($token->type);
                \fwrite(\STDOUT, "[{$token->type}, {$token->literal}]");
            }
        }
    }
}
