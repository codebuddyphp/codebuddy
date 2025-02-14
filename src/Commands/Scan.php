<?php

namespace Codebuddyphp\Codebuddy\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

use function Laravel\Prompts\spin;

final class Scan extends Command
{
    protected $signature = 'cb:scan';

    protected $description = 'Scan codebase issues';

    public function handle(): void
    {
        $this->info('Starting code review...');

        $checks = [
            'rector' => [
                'command' => 'vendor/bin/rector process --dry-run',
                'message' => 'Running Rector analysis...',
            ],
            'pint' => [
                'command' => 'vendor/bin/pint --test',
                'message' => 'Running Pint analysis...',
            ],
            'phpstan' => [
                'command' => 'vendor/bin/phpstan analyse',
                'message' => 'Running PHPStan analysis...',
            ],
            'pest' => [
                'command' => 'vendor/bin/pest',
                'message' => 'Running Pest tests...',
            ],
        ];

        foreach ($checks as $check) {
            $this->review($check);
        }

        $this->info('Code review completed!');
    }

    private function review(array $check): void
    {
        $this->info('> Running '.$check['command']);

        $process = spin(
            fn () => Process::run($check['command']),
            $check['message']
        );

        $this->info($process->output());
    }
}
