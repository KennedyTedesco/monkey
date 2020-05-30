<?php

declare(strict_types=1);

namespace Monkey\Parser;

use Monkey\Ast\Program;
use Monkey\Token\TokenType;

final class ProgramParser
{
    private Parser $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function parse(): Program
    {
        $program = new Program();

        while (!$this->parser->curTokenIs(TokenType::T_EOF)) {
            switch ($this->parser->curToken->type) {
                case TokenType::T_LET:
                    $smtp = (new LetParser($this->parser))->parse();
                    break;
                case TokenType::T_RETURN:
                    $smtp = (new ReturnParser($this->parser))->parse();
                    break;
                default:
                    $smtp = null;
            }

            if (null !== $smtp) {
                $program->append($smtp);
            }

            $this->parser->nextToken();
        }

        return $program;
    }
}
