<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector;
use Rector\CodingStyle\Rector\PostInc\PostIncDecToPreIncDecRector;
use Rector\Config\RectorConfig;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\Set\ValueObject\LevelSetList;

return RectorConfig::configure()
    ->withIndent()
    ->withParallel()
    ->withImportNames(
        removeUnusedImports: true,
    )
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        privatization: true,
        instanceOf: true,
        earlyReturn: true,
        strictBooleans: true,
        rectorPreset: true,
    )
    ->withPhpPolyfill()
    ->withSets([
        LevelSetList::UP_TO_PHP_84,
    ])
    ->withSkip([
        EncapsedStringsToSprintfRector::class,
        PostIncDecToPreIncDecRector::class,
        ReadOnlyPropertyRector::class,
    ])
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);
