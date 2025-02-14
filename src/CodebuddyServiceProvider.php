<?php

namespace Codebuddyphp\Codebuddy;

use Codebuddyphp\Codebuddy\Commands\Configure;
use Codebuddyphp\Codebuddy\Commands\Insights;
use Codebuddyphp\Codebuddy\Commands\Review;
use Illuminate\Support\ServiceProvider;

final class CodebuddyServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {

            $this->commands(
                [
                    Configure::class,
                    Review::class,
                    Insights::class,
                ]
            );

            $this->publishes(
                [
                    __DIR__.'/../config/codebuddy.php' => config_path('codebuddy.php'),
                ],
                'codebuddy-config'
            );
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'codebuddy');
    }

    /**
     * @return array<string>
     */
    public function provides(): array
    {
        return [
            Configure::class,
            Review::class,
            Insights::class,
        ];
    }
}
