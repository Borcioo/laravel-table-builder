<?php

namespace Borek\LaravelTableBuilder\Tables;

use Illuminate\Database\Eloquent\Builder;
use Borek\LaravelTableBuilder\Tables\Contracts\ActionInterface;
use Borek\LaravelTableBuilder\Tables\Contracts\ColumnInterface;
use Borek\LaravelTableBuilder\Tables\Contracts\FilterInterface;
use Borek\LaravelTableBuilder\Tables\Columns\BaseColumn;
use Borek\LaravelTableBuilder\Tables\Columns\TextColumn;
use Borek\LaravelTableBuilder\Tables\Columns\DateColumn;
use Borek\LaravelTableBuilder\Tables\Columns\IconColumn;
use Borek\LaravelTableBuilder\Tables\Columns\ImageColumn;
use Borek\LaravelTableBuilder\Tables\Columns\BadgeColumn;
use Borek\LaravelTableBuilder\Tables\Columns\BooleanColumn;
use Borek\LaravelTableBuilder\Tables\Filters\SelectFilter;
use Borek\LaravelTableBuilder\Tables\Actions\ButtonAction;
use Borek\LaravelTableBuilder\Tables\Filters\TextInputFilter;
use Borek\LaravelTableBuilder\Tables\Filters\NumberFilter;
use Borek\LaravelTableBuilder\Tables\Filters\TernaryFilter;

/**
 * Main class for building and configuring tables.
 * 
 * Provides fluent interface for adding columns, filters, actions
 * and configuring table behavior like sorting, searching and pagination.
 */
class TableBuilder
{
    /**
     * The base query builder instance.
     * 
     * @var Builder
     */
    protected Builder $query;

    /**
     * Array of table columns.
     * 
     * @var ColumnInterface[]
     */
    protected array $columns = [];

    /**
     * Array of searchable column keys.
     * 
     * @var string[]
     */
    protected array $searchable = [];

    /**
     * Array of sortable column keys.
     * 
     * @var string[]
     */
    protected array $sortable = [];

    /**
     * Array of table filters.
     * 
     * @var FilterInterface[]
     */
    protected array $filters = [];

    /**
     * Array of table actions.
     * 
     * @var ActionInterface[]
     */
    protected array $actions = [];

    /**
     * Default number of items per page.
     * 
     * @var int
     */
    protected int $defaultPerPage = 10;

    /**
     * Available options for items per page.
     * 
     * @var int[]
     */
    protected array $perPageOptions = [10, 25, 50, 100];

    /**
     * Create a new TableBuilder instance.
     * 
     * @param Builder $query The base query builder instance
     */
    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    /**
     * Add a column to the table.
     * 
     * @param ColumnInterface $column The column instance to add
     * @return $this
     */
    public function addColumn(ColumnInterface $column): self
    {
        $this->columns[] = $column;
        if ($column instanceof BaseColumn && $column->isSortable()) {
            $this->sortable[] = $column->getKey();
        }
        return $this;
    }

    /**
     * Add a filter to the table.
     * 
     * @param FilterInterface $filter The filter instance to add
     * @return $this
     */
    public function addFilter(FilterInterface $filter): self
    {
        $this->filters[] = $filter;
        return $this;
    }

    /**
     * Add an action to the table.
     * 
     * @param ActionInterface $action The action instance to add
     * @return $this
     */
    public function addAction(ActionInterface $action): self
    {
        $this->actions[] = $action;
        return $this;
    }

    /**
     * Set which columns are searchable.
     * 
     * @param array<string> $columns Array of column keys that should be searchable
     * @return $this
     */
    public function searchable(array $columns): self
    {
        $this->searchable = $columns;
        return $this;
    }

    /**
     * Set which columns are sortable.
     * 
     * @param array<string> $columns Array of column keys that should be sortable
     * @return $this
     */
    public function sortable(array $columns): self
    {
        $this->sortable = $columns;
        foreach ($this->columns as $column) {
            $column->sortable(in_array($column->getKey(), $columns));
        }
        return $this;
    }

    /**
     * Get the complete table schema configuration.
     * 
     * @return array{
     *   columns: array,
     *   filters: array,
     *   actions: array,
     *   searchableColumns: array,
     *   sortableColumns: array,
     *   defaultPerPage: int,
     *   perPageOptions: array
     * }
     */
    public function getSchema(): array
    {
        return [
            'columns' => array_map(fn(ColumnInterface $column) => $column->getConfig(), $this->columns),
            'filters' => array_map(fn(FilterInterface $filter) => $filter->getConfig(), $this->filters),
            'actions' => array_map(fn(ActionInterface $action) => $action->getConfig(), $this->actions),
            'searchableColumns' => $this->searchable,
            'sortableColumns' => $this->sortable,
            'defaultPerPage' => $this->defaultPerPage,
            'perPageOptions' => $this->perPageOptions,
        ];
    }

    /**
     * Get the table data with applied filters, search and pagination.
     * 
     * @param array{
     *   search?: string,
     *   filters?: array<string, mixed>,
     *   sortColumn?: string,
     *   sortDirection?: string,
     *   page?: int,
     *   perPage?: int
     * } $params Request parameters for filtering and pagination
     * @return array{
     *   data: array,
     *   meta: array{
     *     current_page: int,
     *     per_page: int,
     *     total: int,
     *     last_page: int
     *   }
     * } Paginated data with metadata
     */
    public function getData(array $params = []): array
    {
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

        return [
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        ];
    }

    /**
     * Apply search query to the builder.
     * 
     * @param Builder $query The query builder instance
     * @param string $search The search term to apply
     * @return void
     */
    protected function applySearch(Builder $query, string $search): void
    {
        $query->where(function (Builder $query) use ($search) {
            foreach ($this->searchable as $column) {
                if (str_contains($column, '.')) {
                    $parts = explode('.', $column);
                    $relation = $parts[0];
                    $field = $parts[1];

                    $query->orWhereHas($relation, function ($query) use ($field, $search) {
                        $query->where($field, 'like', "%{$search}%");
                    });
                } else {
                    $query->orWhere($column, 'like', "%{$search}%");
                }
            }
        });
    }

    /**
     * Apply filters to the query builder.
     * 
     * @param Builder $query The query builder instance
     * @param array<string, mixed> $filters Array of filter values keyed by filter name
     * @return void
     */
    protected function applyFilters(Builder $query, array $filters): void
    {
        foreach ($filters as $key => $value) {
            $filter = $this->findFilter($key);
            if ($filter) {
                $filter->apply($query, $value);
            }
        }
    }

    /**
     * Find a filter by its key.
     * 
     * @param string $key The filter key to find
     * @return FilterInterface|null The found filter or null
     */
    protected function findFilter(string $key): ?FilterInterface
    {
        foreach ($this->filters as $filter) {
            if ($filter->getKey() === $key) {
                return $filter;
            }
        }
        return null;
    }

    /**
     * Validate and normalize sort direction.
     * 
     * @param string $direction The sort direction to validate
     * @return string The normalized sort direction ('asc' or 'desc')
     */
    protected function validateSortDirection(string $direction): string
    {
        return in_array(strtolower($direction), ['asc', 'desc']) ? strtolower($direction) : 'asc';
    }

    /**
     * Apply sorting to the query builder.
     * 
     * @param Builder $query The query builder instance
     * @param string $column The column to sort by
     * @param string $direction The sort direction ('asc' or 'desc')
     * @return void
     */
    protected function applySort(Builder $query, string $column, string $direction): void
    {
        if (!in_array($column, $this->sortable)) {
            return;
        }

        $direction = $this->validateSortDirection($direction);

        if (str_contains($column, '.')) {
            $parts = explode('.', $column);
            $relation = $parts[0];
            $field = $parts[1];

            $query->orderBy(function ($query) use ($relation, $field) {
                return $query->from($relation)
                    ->whereColumn("{$relation}.id", 'users.id')
                    ->select($field)
                    ->limit(1);
            }, $direction);
        } else {
            $query->orderBy($column, $direction);
        }
    }

    /**
     * Add a text column to the table.
     * 
     * @param string $key The database column or relation.column name
     * @param string $label The display label for the column
     * @return $this
     */
    public function textColumn(string $key, string $label): self
    {
        return $this->addColumn(new TextColumn($key, $label));
    }

    /**
     * Add a date column to the table.
     * 
     * @param string $key The database column or relation.column name
     * @param string $label The display label for the column
     * @return $this
     */
    public function dateColumn(string $key, string $label): self
    {
        return $this->addColumn(new DateColumn($key, $label));
    }

    /**
     * Add an icon column to the table.
     * 
     * @param string $key The database column or relation.column name
     * @param string $label The display label for the column
     * @return $this
     */
    public function iconColumn(string $key, string $label): self
    {
        return $this->addColumn(new IconColumn($key, $label));
    }

    /**
     * Add an image column to the table.
     * 
     * @param string $key The database column or relation.column name
     * @param string $label The display label for the column
     * @return $this
     */
    public function imageColumn(string $key, string $label): self
    {
        return $this->addColumn(new ImageColumn($key, $label));
    }

    /**
     * Add a badge column to the table.
     * 
     * @param string $key The database column or relation.column name
     * @param string $label The display label for the column
     * @return $this
     */
    public function badgeColumn(string $key, string $label): self
    {
        return $this->addColumn(new BadgeColumn($key, $label));
    }

    /**
     * Add a boolean column to the table.
     * 
     * @param string $key The database column or relation.column name
     * @param string $label The display label for the column
     * @return $this
     */
    public function booleanColumn(string $key, string $label): self
    {
        return $this->addColumn(new BooleanColumn($key, $label));
    }

    /**
     * Add a select filter to the table.
     * 
     * @param string $key The database column or relation.column name
     * @param string $label The display label for the filter
     * @param array<string|int, string> $options Available filter options as key-value pairs
     * @return $this
     */
    public function selectFilter(string $key, string $label, array $options): self
    {
        return $this->addFilter(new SelectFilter($key, $label, $options));
    }

    /**
     * Add a button action to the table.
     * 
     * @param string $name The action identifier
     * @param string $label The display label for the action button
     * @param array<string, mixed> $config Additional configuration for the action
     * @return $this
     */
    public function buttonAction(string $name, string $label, array $config = []): self
    {
        return $this->addAction(new ButtonAction($name, $label, $config));
    }

    /**
     * Configure table pagination.
     * 
     * @param int $defaultPerPage Default number of items per page
     * @param array<int> $perPageOptions Available options for items per page
     * @return $this
     */
    public function pagination(int $defaultPerPage = 10, array $perPageOptions = [10, 25, 50, 100]): self
    {
        $this->defaultPerPage = $defaultPerPage;
        $this->perPageOptions = $perPageOptions;
        return $this;
    }

    /**
     * Check if a column is sortable.
     * 
     * @param string $column The column key to check
     * @return bool True if the column is sortable
     */
    public function isSortable(string $column): bool
    {
        return in_array($column, $this->sortable);
    }

    /**
     * Check if a column is searchable.
     * 
     * @param string $column The column key to check
     * @return bool True if the column is searchable
     */
    public function isSearchable(string $column): bool
    {
        return in_array($column, $this->searchable);
    }

    /**
     * Get a column instance by its key.
     * 
     * @param string $key The column key to find
     * @return ColumnInterface|null The found column or null
     */
    public function getColumn(string $key): ?ColumnInterface
    {
        foreach ($this->columns as $column) {
            if ($column->getKey() === $key) {
                return $column;
            }
        }
        return null;
    }

    /**
     * Add a text input filter to the table.
     * 
     * @param string $key The database column or relation.column name
     * @param string $label The display label for the filter
     * @return $this
     */
    public function textFilter(string $key, string $label): self
    {
        return $this->addFilter(new TextInputFilter($key, $label));
    }

    /**
     * Add a number filter to the table.
     * 
     * @param string $key The database column or relation.column name
     * @param string $label The display label for the filter
     * @return $this
     */
    public function numberFilter(string $key, string $label): self
    {
        return $this->addFilter(new NumberFilter($key, $label));
    }

    /**
     * Add a ternary filter to the table.
     * 
     * @param string $key The database column or relation.column name
     * @param string $label The display label for the filter
     * @return $this
     */
    public function ternaryFilter(string $key, string $label): self
    {
        return $this->addFilter(new TernaryFilter($key, $label));
    }
}
