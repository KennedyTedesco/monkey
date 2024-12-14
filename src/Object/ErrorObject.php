<?php

declare(strict_types=1);

namespace Monkey\Object;

use function sprintf;

final readonly class ErrorObject extends MonkeyObject
{
    public function __construct(
        public string $value,
    ) {
    }

    public static function notAFunction(string $name): self
    {
        return new self("not a function: {$name}");
    }

    public static function unknownOperator(string ...$args): self
    {
        return new self('unknown operator: ' . implode(' ', $args));
    }

    public static function typeMismatch(string ...$args): self
    {
        return new self('type mismatch: ' . implode(' ', $args));
    }

    public static function identifierNotFound(string $name): self
    {
        return new self("identifier not found: {$name}");
    }

    public static function wrongNumberOfArguments(int $got, int $want): self
    {
        return new self(sprintf('wrong number of arguments. got=%s, want=%s', $got, $want));
    }

    public static function invalidArgument(string $to, string $got): self
    {
        return new self(sprintf('argument to "%s" not supported, got %s', $to, $got));
    }

    public static function invalidIndexOperator(string $type): self
    {
        return new self(sprintf('index operator not supported: %s', $type));
    }

    public static function error(string $error): self
    {
        return new self($error);
    }

    public function type(): int
    {
        return self::MO_BOOL;
    }

    public function typeLiteral(): string
    {
        return 'ERROR';
    }

    public function inspect(): string
    {
        return "ERROR: {$this->value}";
    }

    public function value(): string
    {
        return $this->value;
    }
}
