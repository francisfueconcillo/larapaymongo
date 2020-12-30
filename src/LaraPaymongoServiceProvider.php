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
        $this->app->make('PepperTech\LaraPaymongo\SamplePurchaseController');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $this->publishes([ 
            __DIR__ . '/resources/js/components' => resource_path('js/components'),
        ], 'vue-components');

        if (env('APP_ENV') == 'local') {
            $this->loadRoutesFrom(__DIR__.'/routes.php');
            $this->loadViewsFrom(__DIR__.'/resources/views', 'larapaymongo');
        }
    }
}
