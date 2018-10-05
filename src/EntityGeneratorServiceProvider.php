<?php

namespace Cotlet\EntityGenerator;

use Illuminate\Support\ServiceProvider;

class EntityGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/Configs/entity-generator.php' => config_path('entity-generator.php')
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__ . '/Configs/entity-generator.php', 'entity-generator'
        );

        $this->publishes([
            __DIR__ . '/Resources/Views/layout.blade.php' => resource_path('views/vendor/entity-generator'),
        ]);

        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        $this->loadViewsFrom(__DIR__ . '/Resources/Views', 'entity-generator');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
