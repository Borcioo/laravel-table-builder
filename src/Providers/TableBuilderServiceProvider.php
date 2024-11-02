<?php

namespace Borek\LaravelTableBuilder\Providers;

use Borek\LaravelTableBuilder\Console\Commands\{
    InstallComponentsCommand,
    InstallDependenciesCommand
};
use Borek\LaravelTableBuilder\Tables\TableBuilder;
use Illuminate\Support\ServiceProvider;

class TableBuilderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('table-builder', function ($app) {
            return new TableBuilder();
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/../../config/table-builder.php' => config_path('table-builder.php'),
            ], 'table-builder-config');

            $this->publishes([
                __DIR__ . '/../../resources/js' => resource_path('js'),
            ], 'table-builder-assets');

            $this->commands([
                InstallDependenciesCommand::class,
                InstallComponentsCommand::class,
            ]);
        }
    }
}
