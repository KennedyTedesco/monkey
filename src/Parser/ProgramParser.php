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
                    $smtp = (new LetParser())($parser);
                    break;
                case TokenType::T_RETURN:
                    $smtp = (new ReturnParser())($parser);
                    break;
                default:
                    $smtp = null;
            }

            if (null !== $smtp) {
                $program->append($smtp);
            }

            $parser->nextToken();
        }

        return $program;
    }
}
