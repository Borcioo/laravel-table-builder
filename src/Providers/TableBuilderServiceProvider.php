<?php

namespace Borek\LaravelTableBuilder\Providers;

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

    public function boot(): void {}
}
