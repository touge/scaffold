<?php

namespace Touge\Scaffold;

use Illuminate\Support\ServiceProvider;
use Touge\Scaffold\Services\ClassificationService;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the module services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
    /**
     * Register the module services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('classification', function () {
            return new ClassificationService();
        });
    }
}
