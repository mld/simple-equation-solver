<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Assign\CombinedAssignRector;
use Rector\CodingStyle\Rector\FuncCall\StrictArraySearchRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Cast\RecastingRemovalRector;
use Rector\Set\ValueObject\SetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    // uncomment to reach your current PHP version
    ->withPhpSets()
    ->withTypeCoverageLevel(0)
    ->withDeadCodeLevel(100)
    ->withCodeQualityLevel(100)
    ->withSets([SetList::CODING_STYLE])
    ->withSkip([StrictArraySearchRector::class])
    ->withSkip([
        CombinedAssignRector::class => [
            __DIR__ . '/src/Solver.php',
        ],
        RecastingRemovalRector::class => [
            __DIR__ . '/src/Solver.php',
        ],
    ]);;
