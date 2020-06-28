<?php

declare(strict_types=1);

namespace Monkey;

use Monkey\Evaluator\Environment;
use Monkey\Object\OutputObject;
use Monkey\Repl\Repl;

function help(): string
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

switch ($argv[1]) {
    case 'repl':
        Repl::start();
        break;
    case 'run':
        $object = Repl::eval(\file_get_contents($argv[2]), new Environment());
        if ($object instanceof OutputObject) {
            echo $object->value();
        }
        break;
    default:
        help();
}
