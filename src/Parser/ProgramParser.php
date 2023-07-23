<?php

declare(strict_types=1);

namespace Monkey\Parser;

use Monkey\Ast\Program;
use Monkey\Parser\Statements\StatementParser;
use Monkey\Token\TokenType;

final class ProgramParser
{
    public function __invoke(Parser $parser): Program
    {
        $program = new Program();

        while (!$parser->curToken()->is(TokenType::EOF)) {
            $statement = (new StatementParser())($parser);
            $program->addStatement($statement);

            $parser->nextToken();
        }

        return $program;
    }
}
