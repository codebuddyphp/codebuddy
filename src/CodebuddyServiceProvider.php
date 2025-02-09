<?php

namespace Codebuddyphp\Codebuddy;

use Codebuddyphp\Codebuddy\Commands\Configure;
use Illuminate\Support\ServiceProvider;

class CodebuddyServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register bindings if any
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Configure::class,
            ]);

            $this->publishes([
                __DIR__.'/../config/codebuddy.php' => config_path('codebuddy.php'),
            ]);
        }
    }

    public function provides()
    {
        return [
            Configure::class,
        ];
    }
}