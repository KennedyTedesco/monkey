<?php

declare(strict_types=1);

namespace MonkeyLang\Lang\Evaluator;

use MonkeyLang\Lang\Ast\Expressions\IdentifierExpression;
use MonkeyLang\Lang\Object\ErrorObject;
use MonkeyLang\Lang\Object\MonkeyObject;

final readonly class EvalIdentifier
{
    public function __construct(public Environment $environment)
    {
    }

    public function __invoke(IdentifierExpression $identifierExpression): MonkeyObject
    {
        $object = $this->environment->get($identifierExpression->value) ?? BuiltinFunction::get($identifierExpression->value);

        if ($object instanceof MonkeyObject) {
            return $object;
        }

        return ErrorObject::identifierNotFound($identifierExpression->value);
    }
}
