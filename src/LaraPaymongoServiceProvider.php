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

        $this->publishes([ 
            __DIR__ . '/../resources/js/components' => resource_path('js/components'),
            __DIR__ . '/../resources/config/larapaymongo.php' => app()->configPath('larapaymongo.php'),
            __DIR__ . '/../resources/LaraPaymongoIntegrator.php' => app_path('LaraPaymongoIntegrator.php'),
        ], 'larapaymongo');

        $this->mergeConfigFrom(__DIR__ . '/../resources/config/larapaymongo.php', 'larapaymongo');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadViewsFrom(__DIR__.'/Views', 'larapaymongo');
    }
}
