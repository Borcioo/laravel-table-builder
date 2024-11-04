<?php

namespace Borek\LaravelTableBuilder\Tables\Concerns;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Events\QueryExecuted;

trait HasDebug
{
    protected bool $debugEnabled = false;
    protected array $debugData = [
        'total_query_time' => 0,
        'query_count' => 0,
        'queries' => [],
    ];

    public function debug(): self
    {
        $this->debugEnabled = true;

        DB::listen(function (QueryExecuted $query) {
            if ($this->debugEnabled) {
                $this->debugData['queries'][] = [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time,
                ];

                $this->debugData['total_query_time'] += $query->time;
                $this->debugData['query_count']++;
            }
        });

        return $this;
    }

    public function getDebugData(): ?array
    {
        return $this->debugEnabled ? $this->debugData : null;
    }
}
