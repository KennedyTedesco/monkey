<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ClassMethod\UnSpreadOperatorRector;
use Rector\CodingStyle\Rector\Encapsed\EncapsedStringsToSprintfRector;
use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
    $parameters->set(Option::IMPORT_DOC_BLOCKS, false);
    $parameters->set(Option::IMPORT_SHORT_CLASSES, true);

    $parameters->set(Option::PATHS, [
        __DIR__.'/src',
    ]);

    $parameters->set(Option::SKIP, [
        UnSpreadOperatorRector::class,
        EncapsedStringsToSprintfRector::class,
    ]);

    $containerConfigurator->import(SetList::DEAD_CODE);
    $containerConfigurator->import(SetList::CODE_QUALITY);
    $containerConfigurator->import(SetList::CODING_STYLE);
    $containerConfigurator->import(SetList::CODING_STYLE_ADVANCED);
    $containerConfigurator->import(SetList::EARLY_RETURN);
    $containerConfigurator->import(SetList::TYPE_DECLARATION_STRICT);
    $containerConfigurator->import(SetList::PHP_80);
    $containerConfigurator->import(SetList::PHP_81);
};
