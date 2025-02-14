<?php

declare(strict_types=1);

return [
    'checks' => [
        'rector' => [
            'package' => 'driftingly/rector-laravel',
            'stub_file' => 'rector.php.stub',
            'config_file' => 'rector.php',
            'dry_run' => 'vendor/bin/rector process --dry-run --memory-limit=-1',
            'auto_fix' => 'vendor/bin/rector process --memory-limit=-1',
            'tags' => ['code-quality', 'auto-upgrades'],
        ],
        'pint' => [
            'package' => 'laravel/pint',
            'stub_file' => 'pint.json.stub',
            'config_file' => 'pint.json',
            'dry_run' => 'vendor/bin/pint --test',
            'auto_fix' => 'vendor/bin/pint',
            'tags' => ['code-styling'],
        ],
        'phpstan' => [
            'package' => 'larastan/larastan',
            'stub_file' => 'phpstan.neon.stub',
            'config_file' => 'phpstan.neon',
            'dry_run' => 'vendor/bin/phpstan analyse --memory-limit=-1',
            'auto_fix' => null,
            'tags' => ['static-analysis'],
        ],
        'pest' => [
            'package' => 'pestphp/pest',
            'stub_file' => 'phpunit.xml.stub',
            'config_file' => 'phpunit.xml',
            'dry_run' => 'vendor/bin/pest --compact',
            'auto_fix' => null,
            'tags' => ['testing', 'unit-testing', 'feature-testing', 'mutation-testing'],
        ],
    ],
    'alerts' => [
        'slack' => [
            'webhook_url' => env('CODEBUDDY_SLACK_WEBHOOK_URL', ''),
            'channel' => '',
            'username' => null,
            'icon' => null,
        ],
    ],
];
