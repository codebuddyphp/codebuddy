<?php

namespace Codebuddyphp\Codebuddy\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\spin;

final class Setup extends Command
{
    protected $signature = 'cb:setup';

    protected $description = 'Configures essential packages for your project';

    public function handle(): void
    {
        $packages = [
            [
                'package_name' => 'driftingly/rector-laravel',
                'stub_file' => 'rector.php.stub',
                'config_file' => 'rector.php',
                'description' => 'Rector makes upgrading and maintaining code easier.',
            ],
            [
                'package_name' => 'larastan/larastan',
                'stub_file' => 'phpstan.neon.stub',
                'config_file' => 'phpstan.neon',
                'description' => 'PHPStan helps detect errors at compile time instead of runtime.',
            ],
            [
                'package_name' => 'laravel/pint',
                'stub_file' => 'pint.json.stub',
                'config_file' => 'pint.json',
                'description' => 'Pint ensures your code follows consistent formatting rules.',
            ],
            [
                'package_name' => 'pestphp/pest',
                'stub_file' => 'phpunit.xml.stub',
                'config_file' => 'phpunit.xml',
                'description' => 'Elegant testing framework.',
            ],
        ];

        foreach ($packages as $package) {
            $this->configurePackage($package);
        }

        $confirmed = confirm('Setup completed. Do you want to scan codebase?');

        if ($confirmed) {
            $this->call('cb:scan');
        }
    }

    private function configurePackage(array $package): void
    {
        $this->newLine();

        spin(
            callback: function () use ($package): void {
                sleep(1);
                $result = File::copy(
                    __DIR__.'/../../stubs/'.$package['stub_file'],
                    base_path($package['config_file'])
                );
                if (! $result) {
                    self::fail(
                        sprintf('%s: failed to configure', $package['package_name'])
                    );
                }
            },
            message: sprintf('Configuring %s...', $package['package_name']));

        $this->info(sprintf('%s: configured', $package['package_name']));
    }
}
