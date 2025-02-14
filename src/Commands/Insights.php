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
            'PHPStan' => $this->runPHPStan(),
            'Rector' => $this->runPhpCsFixer(),
            'Pint' => $this->runPint(),
            'Pest' => $this->runPestCoverage(),
        ];

        table(
            ['Metric', 'Score', 'Error'],
            array_map(fn ($key, $value): array => [$key, $value['score'], $value['error']], array_keys($insights), $insights)
        );

        $overallScore = $this->calculateScore($insights);

        if ($overallScore >= 80) {
            $this->info("Overall Code Quality Score: $overallScore/100 ✅");
        } elseif ($overallScore >= 50) {
            $this->warn("Overall Code Quality Score: $overallScore/100 ⚠️");
        } else {
            $this->error("Overall Code Quality Score: $overallScore/100 ❌");
        }
    }

    private function runPHPStan(): array
    {
        return $this->executeCommand(['vendor/bin/phpstan', 'analyse', '--no-progress', '--level=max']);
    }

    private function runPhpCsFixer(): array
    {
        return $this->executeCommand(['vendor/bin/rector', '--dry-run']);
    }

    private function runPint(): array
    {
        return $this->executeCommand(['vendor/bin/pint', '--test']);
    }

    private function runPestCoverage(): array
    {
        return $this->executeCommand(['vendor/bin/pest', '--coverage']);
    }

    private function executeCommand(array $command): array
    {
        return spin(function () use ($command): array {
            $process = new SymfonyProcess($command);
            $process->run();

            return [
                'score' => $process->isSuccessful() ? 100 : 50,
                'error' => $process->isSuccessful() ? 'None' : trim($process->getErrorOutput()),
            ];
        }, 'Running '.implode(' ', $command));
    }

    private function calculateScore(array $insights): int
    {
        $totalScore = array_sum(array_column($insights, 'score'));

        return (int) ($totalScore / count($insights));
    }
}
