<?php

namespace Touge\Scaffold;

use Illuminate\Support\ServiceProvider;
use Touge\Scaffold\Supports\StructureTree;

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
    public function register(){
        $this->app->singleton('structure.tree', function () {
            return new StructureTree();
        });
    }
}
