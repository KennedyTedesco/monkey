<?php

declare(strict_types=1);

namespace Monkey\Evaluator;

use Monkey\Ast\Expressions\IdentifierExpression;
use Monkey\Object\ErrorObject;
use Monkey\Object\MonkeyObject;

final readonly class EvalIdentifier
{
    public function __construct(private Environment $environment)
    {
    }

    public function __invoke(IdentifierExpression $identifierExpression): MonkeyObject
    {
        $object = $this->environment->get($identifierExpression->value()) ?? BuiltinFunction::get($identifierExpression->value());

        if ($object instanceof MonkeyObject) {
            return $object;
        }

        return ErrorObject::identifierNotFound($identifierExpression->value());
    }
}
