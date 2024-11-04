<?php

namespace Borek\LaravelTableBuilder\Tables\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface TableInterface
{
    public static function make(Builder $query): self;

    public function getSchema(): array;

    public function getData(array $params): array;
}
