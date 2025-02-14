<?php

namespace Codebuddyphp\Codebuddy\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Process;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\spin;

final class Review extends Command
{
    protected $signature = 'cb:review {--fix : Fix issues automatically}';

    protected $description = 'Find or fix issues (rector, Pint, PHPStan, Pest, etc)';

    private bool $autoFix = false;

    private bool $promptToFix = false;

    public function handle(): void
    {
        $this->autoFix = $this->option('fix');

        $this->newLine();
        $this->info($this->autoFix ? 'Fixing issues...' : 'Finding issues...');
        $this->newLine();

        $checks = Config::get('codebuddy.checks');

        if (! is_array($checks)) {
            $this->warn('Vendor assets are not published. Please publish it first and re-run this command.');

            return;
        }

        foreach ($checks as $check => $config) {
            $this->review($check, $config);
            $this->newLine();
        }

        $this->info('Done!');

        if ($this->promptToFix) {
            $confirmed = confirm('One or more checks are failed. Do you want to auto-fix them?');

            if ($confirmed) {
                $this->call(Review::class, ['--fix' => true]);
            }
        }
    }

    private function review(string $check, array $config): void
    {
        $this->info(
            sprintf('🚀 Running  %s (mode: %s)', ucfirst($check), $this->autoFix ? 'fix' : 'dry-run')
        );

        $command = $config['dry_run'];

        if ($this->autoFix) {
            if (is_null($config['auto_fix'])) {
                $this->warn('Auto-fixing mode is not supported for this check. Fix manually (if failing).');

                return;
            }

            $command = $config['auto_fix'];
        }

        $process = spin(
            fn () => Process::run($command),
            ''
        );

        if (! $process->successful()) {
            $this->promptToFix = true;
        }

        $this->info($process->output());
    }
}
