<?php

namespace Codebuddyphp\Codebuddy\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use function Termwind\render;

class Configure extends Command
{
    protected $signature = 'codebuddy:configure';

    protected $description = 'Configure Rector, Larastan (PHPStan) & Pint';

    public function handle(): void
    {
        $filesystem = new Filesystem();

        $configs = [
            'rector.php',
            'phpstan.neon',
            'pint.json',
        ];

        foreach ($configs as $file) {
            $sourceFile = __DIR__ . "/../../config/laravel/{$file}";
            $destinationFile = base_path($file);

            if (!$filesystem->exists($sourceFile)) {
                $this->error("Source file not found: {$sourceFile}");
                continue;
            }

            if ($filesystem->exists($destinationFile)) {
                $overwrite = $this->confirmOverwrite($destinationFile);
                if (!$overwrite) {
                    $this->warn("Skipped: {$destinationFile} already exists");
                    continue;
                }
            }

            $filesystem->copy($sourceFile, $destinationFile);
            $this->info("Copied: {$file} to project root");
            $this->newLine();
        }
    }

    private function confirmOverwrite(string $file): bool
    {
        $this->info(
            sprintf('%s already exists. Do you want to overwrite it?', $file)
        );

        return $this->ask('Overwrite?', false);
    }
}
