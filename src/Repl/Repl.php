<?php

declare(strict_types=1);

namespace Monkey\Repl;

use Monkey\Evaluator\Environment;
use Monkey\Evaluator\Evaluator;
use Monkey\Lexer\Lexer;
use Monkey\Object\MonkeyObject;
use Monkey\Object\NullObject;
use Monkey\Parser\Parser;
use Monkey\Parser\ProgramParser;

final class Repl
{
    public static function start(): void
    {
        fwrite(\STDOUT, <<<TEXT
                    __,__
           .--.  .-"     "-.  .--.
          / .. \/  .-. .-.  \/ .. \
         | |  '|  /   Y   \  |'  | |
         | \   \  \ 0 | 0 /  /   / |
          \ '- ,\.-"`` ``"-./, -' /
           `'-' /_   ^ ^   _\ '-'`
               |  \._   _./  |
               \   \ `~` /   /
                '._ '-=-' _.'
                   '~---~'
        -------------------------------
        | Monkey Programming Language |
        -------------------------------\n\n
        TEXT);

        $env = new Environment();
        while (true) {
            $input = readline("\n > ");
            if ('exit' === $input) {
                return;
            }

            self::evalAndInspect($input, $env);
        }
    }

    public static function eval(string $input, Environment $env): ?MonkeyObject
    {
        $parser = new Parser(new Lexer($input));

        if ([] !== $parser->errors()) {
            echo self::getErrors($parser->errors());

            return null;
        }

        return (new Evaluator())->eval((new ProgramParser())($parser), $env);
    }

    public static function evalAndInspect(string $input, Environment $env): void
    {
        $object = self::eval($input, $env);
        if (!$object instanceof MonkeyObject) {
            return;
        }

        if ($object instanceof NullObject) {
            return;
        }

        echo $object->inspect().\PHP_EOL;
    }

    private static function getErrors(array $errors): string
    {
        $out = '';
        foreach ($errors as $index => $error) {
            $out .= "{$index}) {$error}";
        }

        return $out;
    }
}
