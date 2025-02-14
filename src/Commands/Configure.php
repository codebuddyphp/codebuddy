<?php

namespace Codebuddyphp\Codebuddy\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\spin;

final class Configure extends Command
{
    protected $signature = 'cb:configure';

    protected $description = 'Configures essential packages for your project';

    public function handle(): void
    {
        $packages = Config::get('codebuddy.checks');

        if (! is_array($packages)) {
            $this->warn('Vendor assets are not published. Please publish it first and re-run this command.');

            return;
        }

        foreach ($packages as $package) {
            $this->configurePackage($package);
        }

        $confirmed = confirm('Setup completed. Do you want to review codebase?');

        if ($confirmed) {
            $this->call(Review::class);
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
                        sprintf('%s: failed to configure', $package['package'])
                    );
                }
            },
            message: sprintf('🚀 Configuring %s...', $package['package']));

        $this->info(sprintf('✅ %s: configured', $package['package']));
    }
}
