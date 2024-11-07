<?php

namespace Borek\LaravelTableBuilder\Tables;

use Borek\LaravelTableBuilder\Tables\Contracts\TableInterface;
use Illuminate\Database\Eloquent\Builder;
use Borek\LaravelTableBuilder\Tables\Concerns\HasColumns;
use Borek\LaravelTableBuilder\Tables\Concerns\HasFilters;
use Borek\LaravelTableBuilder\Tables\Concerns\HasActions;
use Borek\LaravelTableBuilder\Tables\Concerns\HasSearch;
use Borek\LaravelTableBuilder\Tables\Concerns\HasSort;
use Borek\LaravelTableBuilder\Tables\Concerns\HasPagination;
use Borek\LaravelTableBuilder\Tables\Concerns\HasTransformers;
use Borek\LaravelTableBuilder\Tables\Concerns\HasDebug;
use Borek\LaravelTableBuilder\Tables\Concerns\HasCache;


class TableBuilder implements TableInterface
{
    use HasColumns;
    use HasFilters;
    use HasActions;
    use HasSearch;
    use HasSort;
    use HasPagination;
    use HasTransformers;
    use HasDebug;
    use HasCache;

    protected Builder $query;

    public function __construct() {}

    public static function make(Builder $query): self
    {
        $instance = new self();
        $instance->query = $query;
        return $instance;
    }

    public function getSchema(): array
    {
        return [
            'columns' => $this->getColumns(),
            'filters' => $this->getFilters(),
            'actions' => $this->getActions(),
            'searchableColumns' => $this->searchable,
            'sortableColumns' => $this->getAllSortableColumns(),
            'defaultPerPage' => $this->defaultPerPage,
            'perPageOptions' => $this->perPageOptions,
        ];
    }

    public function getData(array $params): array
    {
        if ($cachedData = $this->getDataFromCache($params)) {
            return $cachedData;
        }

        $query = clone $this->query;

        if (!empty($params['search'])) {
            $this->applySearch($query, $params['search']);
        }

        if (!empty($params['filters'])) {
            $this->applyFilters($query, $params['filters']);
        }

        if (!empty($params['sortColumn'])) {
            $this->applySort(
                $query,
                $params['sortColumn'],
                $params['sortDirection'] ?? 'asc'
            );
        }

        $paginator = $query->paginate(
            $params['perPage'] ?? $this->defaultPerPage
        );

        $result = [
            'data' => $this->transformData($paginator->items()),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        ];

        if ($debugData = $this->getDebugData()) {
            $result['debug'] = $debugData;
        }

        $this->storeInCache($params, $result);

        return $result;
    }

    public function getAllSortableColumns(): array
    {
        return array_unique(
            array_merge($this->sortableFields, $this->sortableColumns)
        );
    }
}
