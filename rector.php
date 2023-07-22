<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->parallel();
    $rectorConfig->importNames();
    $rectorConfig->removeUnusedImports();
    $rectorConfig->importShortClasses();

    $rectorConfig->autoloadPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $rectorConfig->skip([
        InlineConstructorDefaultToPropertyRector::class,
    ]);

    $rectorConfig->import(SetList::CODE_QUALITY);
    $rectorConfig->import(SetList::EARLY_RETURN);
    $rectorConfig->import(SetList::TYPE_DECLARATION);
    $rectorConfig->import(LevelSetList::UP_TO_PHP_82);
    $rectorConfig->import(SetList::DEAD_CODE);
    $rectorConfig->import(SetList::STRICT_BOOLEANS);
    $rectorConfig->import(SetList::INSTANCEOF);
};
