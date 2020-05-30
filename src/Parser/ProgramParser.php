<?php

declare(strict_types=1);

namespace Monkey\Parser;

use Monkey\Ast\Program;
use Monkey\Token\TokenType;

final class ProgramParser
{
    public function __invoke(Parser $parser): Program
    {
        $program = new Program();

        while (!$parser->curTokenIs(TokenType::T_EOF)) {
            switch ($parser->curToken->type) {
                case TokenType::T_LET:
                    $statement = (new LetParser())($parser);
                    break;
                case TokenType::T_RETURN:
                    $statement = (new ReturnParser())($parser);
                    break;
                default:
                    $statement = null;
            }

            if (null !== $statement) {
                $program->append($statement);
            }

            $parser->nextToken();
        }

        return $program;
    }
}
