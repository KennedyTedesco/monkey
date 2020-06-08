<?php

declare(strict_types=1);

namespace Monkey\Parser\Parselet;

use Monkey\Ast\Expressions\CallExpression;
use Monkey\Ast\Expressions\Expression;
use Monkey\Parser\CallArgumentsParser;
use Monkey\Parser\Parser;

final class CallExpressionParselet implements InfixParselet
{
    private Parser $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function parse(Expression $function): Expression
    {
        $arguments = (new CallArgumentsParser())($this->parser);

        return new CallExpression($this->parser->curToken, $function, $arguments);
    }
}
