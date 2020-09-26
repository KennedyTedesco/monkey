<?php

declare(strict_types=1);

namespace Tests;

function getExampleDetails(string $filename): array
{
    $details = [];
    \preg_match('/^--TEST--(.*)--FILE--(.*)--EXPECT--(.*)$/is', \file_get_contents($filename), $details);
    return [
        'test' => \trim($details[1]),
        'file' => \trim($details[2]),
        'expect' => \trim($details[3]),
    ];
}

test('eval integer expressionss', function (string $filename) {
    $details = getExampleDetails(__DIR__."/examples/{$filename}");

    \ob_start();
    evalProgram($details['file']);
    $output = \ob_get_clean();

    expect($details['expect'])->toBe($output);
})->with(function () {
    foreach (\glob(__DIR__.'/examples/*.monkey') as $filename) {
        yield \basename($filename);
    }
});
