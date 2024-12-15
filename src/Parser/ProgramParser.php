<?php

declare(strict_types=1);

namespace MonkeyLang\Parser;

use MonkeyLang\Ast\Program;
use MonkeyLang\Ast\Statements\Statement;
use MonkeyLang\Parser\Statements\StatementParser;
use MonkeyLang\Token\TokenType;

final class ProgramParser
{
    public function __invoke(Parser $parser): Program
    {
        $program = new Program();

        while (!$parser->curToken()->is(TokenType::EOF)) {
            $statement = new StatementParser()($parser);

            if ($statement instanceof Statement) {
                $program->addStatement($statement);
            }

            $parser->nextToken();
        }

        return $program;
    }
}
