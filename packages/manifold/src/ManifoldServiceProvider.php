<?php

namespace Manifold\Cms;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Manifold\Cms\Console\MakeCollectionCommand;
use Manifold\Cms\Console\MigrateCommand;

class ManifoldServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/manifold.php', 'manifold');

        $this->app->singleton(Registry::class, fn () => new Registry(config('manifold.collections', [])));
    }

    public function boot(): void
    {
        $this->publishes([__DIR__.'/../config/manifold.php' => config_path('manifold.php')], 'manifold-config');

        Route::prefix(config('manifold.route_prefix', 'api/manifold'))
            ->middleware('api')
            ->group(__DIR__.'/../routes/manifold.php');

        // Registered outside the console too: the schema-edit endpoints
        // run manifold:migrate via Artisan::call inside an HTTP request.
        $this->commands([MigrateCommand::class, MakeCollectionCommand::class]);
    }
}
