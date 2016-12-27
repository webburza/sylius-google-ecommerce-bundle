<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->in(__DIR__);

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules(
        [
            '@Symfony' => true,
            'php_unit_construct' => true,
            'php_unit_strict' => true,
            'phpdoc_order' => true,
            'strict_param' => true,
            'array_syntax' => ['syntax' => 'short'],
            'empty_return' => false,
        ]
    )
    ->setFinder($finder);
