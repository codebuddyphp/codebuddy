<?php

namespace Codebuddyphp\Codebuddy\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process as SymfonyProcess;

use function Laravel\Prompts\info;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\table;

final class Insights extends Command
{
    protected $signature = 'cb:insights';

    protected $description = 'Insights about your codebase';

    public function handle(): void
    {
        info('Running codebase analysis...');

        $insights = [
            'PHPStan Level' => $this->runPHPStan(),
            'PHP CS Fixer' => $this->runPhpCsFixer(),
            'Pint (Laravel Coding Standards)' => $this->runPint(),
            'Tests Coverage' => $this->runPestCoverage(),
        ];

        table(['Metric', 'Score'], array_map(fn ($key, $value): array => [$key, $value], array_keys($insights), $insights));

        $overallScore = $this->calculateScore($insights);

        if ($overallScore >= 80) {
            $this->info("Overall Code Quality Score: $overallScore/100 ✅");
        } elseif ($overallScore >= 50) {
            $this->warn("Overall Code Quality Score: $overallScore/100 ⚠️");
        } else {
            $this->error("Overall Code Quality Score: $overallScore/100 ❌");
        }
    }

    private function runPHPStan(): int
    {
        return $this->executeCommand(['vendor/bin/phpstan', 'analyse', '--no-progress', '--level=max']);
    }

    private function runPhpCsFixer(): int
    {
        return $this->executeCommand(['vendor/bin/php-cs-fixer', 'fix', '--dry-run', '--diff']);
    }

    private function runPint(): int
    {
        return $this->executeCommand(['vendor/bin/pint', '--test']);
    }

    private function runPestCoverage(): int
    {
        return $this->executeCommand(['vendor/bin/pest', '--coverage']);
    }

    private function executeCommand(array $command): int
    {
        return spin(function () use ($command): int {
            $process = new SymfonyProcess($command);
            $process->run();

            return $process->isSuccessful() ? 100 : 50;
        }, 'Running '.implode(' ', $command));
    }

    private function calculateScore(array $insights): int
    {
        return (int) (array_sum($insights) / count($insights));
    }
}
