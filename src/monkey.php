<?php

declare(strict_types=1);

namespace Monkey;

use Monkey\Evaluator\Environment;
use Monkey\Repl\Repl;

function help(): never
{
    echo <<<HELP
    Usage: monkey [command]

    Commands:
        repl
            Start the Repl.
        run file.mk
            Runs a file contents.
        help
            This help.
    HELP;

    exit;
}

if ($argc <= 1) {
    help();
}

match ($argv[1]) {
    'repl' => Repl::start(),
    'run' => Repl::eval(file_get_contents($argv[2]), new Environment()),
    default => help(),
};
