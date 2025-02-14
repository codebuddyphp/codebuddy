<?php

namespace Codebuddyphp\Codebuddy;

use Codebuddyphp\Codebuddy\Commands\Scan;
use Codebuddyphp\Codebuddy\Commands\Setup;
use Illuminate\Support\ServiceProvider;

final class CodebuddyServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {

            $this->commands(
                [
                    Setup::class,
                    Scan::class,
                ]
            );

            $this->publishes([
                __DIR__.'/../config/codebuddy.php' => config_path('codebuddy.php'),
            ]);
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'codebuddy');
    }

    /**
     * @return array<string>
     */
    public function provides(): array
    {
        return [
            Setup::class,
            Scan::class,
        ];
    }
}
