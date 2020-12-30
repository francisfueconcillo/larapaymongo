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
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([ 
            __DIR__ . '/resources/js/components/*' => resource_path('js/components'),
        ], 'vue-components');
    }
}
