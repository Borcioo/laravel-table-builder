<?php

namespace Borek\LaravelTableBuilder\Facades;

use Illuminate\Support\Facades\Facade;

class TableBuilder extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'table-builder';
    }
}
