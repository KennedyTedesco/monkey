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

use const PHP_EOL;
use const STDOUT;

final class Repl
{
    public static function start(): void
    {
        fwrite(STDOUT, <<<TEXT
                    __,__
           .--.  .-"     "-.  .--.
          / .. \\/  .-. .-.  \\/ .. \\
         | |  '|  /   Y   \\  |'  | |
         | \\   \\  \\ 0 | 0 /  /   / |
          \\ '- ,\\.-"`` ``"-./, -' /
           `'-' /_   ^ ^   _\\ '-'`
               |  \\._   _./  |
               \\   \\ `~` /   /
                '._ '-=-' _.'
                   '~---~'
        -------------------------------
        | Monkey Programming Language |
        -------------------------------\n\n
        TEXT);

        $environment = new Environment();

        while (true) {
            $input = readline("\n > ");

            if ($input === 'exit') {
                return;
            }

            self::evalAndInspect($input, $environment);
        }
    }

    public static function eval(string $input, Environment $environment): ?MonkeyObject
    {
        $parser = new Parser(new Lexer($input));

        if ($parser->errors() !== []) {
            echo self::getErrors($parser->errors());

            return null;
        }

        return (new Evaluator())->eval((new ProgramParser())($parser), $environment);
    }

    public static function evalAndInspect(string $input, Environment $environment): void
    {
        $monkeyObject = self::eval($input, $environment);

        if (!$monkeyObject instanceof MonkeyObject) {
            return;
        }

        if ($monkeyObject instanceof NullObject) {
            return;
        }

        echo $monkeyObject->inspect() . PHP_EOL;
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
