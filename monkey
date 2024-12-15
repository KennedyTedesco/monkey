#!/usr/bin/env php
<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Monkey\Monkey\Command\CommandFactory;
use Monkey\Monkey\Command\CommandRunner;
use Monkey\Monkey\Config\ConfigurationManager;
use Monkey\Monkey\IO\ConsoleOutput;
use Monkey\Monkey\IO\InputReader;
use Monkey\Monkey\IO\OutputFormatter;
use Monkey\Monkey\Monkey;
use Monkey\Monkey\Performance\PerformanceTracker;
use Monkey\Monkey\Repl\ReplManager;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\ArgvInput;

if (PHP_SAPI !== 'cli') {
    exit;
}

if (!isset($GLOBALS['argv']) || !is_array($GLOBALS['argv'])) {
    exit(1);
}

try {
    $input = new ArgvInput();
    $output = new ConsoleOutput();
    $questionHelper = new QuestionHelper();

    $outputFormatter = new OutputFormatter($output);
    $inputReader = new InputReader($input, $output, $questionHelper);
    $performanceTracker = new PerformanceTracker();
    $replManager = new ReplManager(
        $inputReader,
        $outputFormatter,
        $performanceTracker
    );

    $commandFactory = new CommandFactory(
        //$inputReader,
        $outputFormatter,
        $performanceTracker,
        $replManager
    );
    $commandRunner = new CommandRunner($commandFactory);
    $configManager = new ConfigurationManager();

    $monkey = new Monkey($commandRunner, $configManager);
    exit($monkey->run($GLOBALS['argv']));
} catch (Throwable $e) {
    fwrite(STDERR, "Fatal error: {$e->getMessage()}\n");
    exit(1);
}
