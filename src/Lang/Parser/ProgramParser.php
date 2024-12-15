<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Parser;

use MonkeyLang\Lang\Ast\Program;
use MonkeyLang\Lang\Ast\Statements\Statement;
use MonkeyLang\Lang\Parser\Statements\StatementParser;
use MonkeyLang\Lang\Token\TokenType;

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
