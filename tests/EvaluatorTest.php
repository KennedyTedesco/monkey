<?php

declare(strict_types=1);

namespace Tests;

test('eval integer expression', function (string $input, int $expected) {
    testIntegerObject(evalProgram($input), $expected);
})->with([
    ['10', 10],
    ['100', 100],
    ['1000', 1000],
    ['10000', 10000],
    ['100000', 100000],
    ['1000000', 1000000],
    [(string) \PHP_INT_MAX, \PHP_INT_MAX],
]);
