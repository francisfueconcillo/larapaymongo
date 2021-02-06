<?php

namespace PepperTech\LaraPaymongo;

use Illuminate\Support\ServiceProvider;

class LaraPaymongoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->make('PepperTech\LaraPaymongo\SamplePurchaseController');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        // $path = app_path('Http/Controllers/Controller.php');

        $this->publishes([ 
            __DIR__ . '/../resources/js/components' => resource_path('js/components'),
            __DIR__ . '/../resources/config/larapaymongo.php' => app()->configPath('larapaymongo.php'),
        ], 'larapaymongo');

        $this->mergeConfigFrom(__DIR__ . '/../resources/config/larapaymongo.php', 'larapaymongo');

        if (env('APP_ENV') == 'local') {
            $this->loadRoutesFrom(__DIR__.'/routes.php');
            $this->loadViewsFrom(__DIR__.'/views', 'larapaymongo');
        }
    }
}
