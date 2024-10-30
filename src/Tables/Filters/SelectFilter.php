<?php

namespace Borek\LaravelTableBuilder\Tables\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * Filter for select/dropdown inputs.
 * 
 * Allows filtering by selecting one option from a predefined list.
 * Supports both direct column and relationship filtering.
 */
class SelectFilter extends BaseFilter
{
    /**
     * Get the filter type identifier.
     * 
     * @return string
     */
    protected static function getType(): string
    {
        return 'select';
    }

    /**
     * Create a new select filter instance.
     * 
     * @param string $key The database column or relation.column name
     * @param string $label The display label for the filter
     * @param array<string|int, string> $options Available filter options as key-value pairs
     */
    public function __construct(
        string $key,
        string $label,
        array $options
    ) {
        parent::__construct($key, $label, 'select', $options);
    }

    /**
     * Apply the filter's constraints to the query.
     * 
     * @param Builder $query The query builder instance
     * @param mixed $value The selected option value
     * @return void
     */
    public function apply(Builder $query, $value): void
    {
        if ($value === '' || $value === null) {
            return;
        }

        if ($this->isRelation()) {
            $parts = explode('.', $this->key);
            $relation = $parts[0];
            $column = $parts[1];

            $query->whereHas($relation, function ($query) use ($column, $value) {
                $query->where($column, $value);
            });
        } else {
            $query->where($this->key, $value);
        }
    }
}
