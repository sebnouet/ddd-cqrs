<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in([__DIR__.'/src', __DIR__.'/features/bootstrap', __DIR__.'/tests'])
    ->append([__DIR__.'/fleet']);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PHP8x3Migration' => true,
        'declare_strict_types' => true,
    ])
    ->setFinder($finder);
