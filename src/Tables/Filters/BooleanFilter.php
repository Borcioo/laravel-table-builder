<?php

namespace Borek\LaravelTableBuilder\Tables\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * Filter for boolean/toggle values.
 * 
 * Allows filtering by true/false values with customizable labels.
 */
class BooleanFilter extends BaseFilter
{
    /**
     * Get the filter type identifier.
     * 
     * @return string
     */
    protected static function getType(): string
    {
        return 'boolean';
    }

    /**
     * Create a new boolean filter instance.
     * 
     * @param string $key The database column or relation.column name
     * @param string $label The display label for the filter
     */
    public function __construct(
        string $key,
        string $label
    ) {
        parent::__construct($key, $label, 'boolean', [
            true => 'Tak',
            false => 'Nie'
        ]);
    }

    /**
     * Apply the filter's constraints to the query.
     * 
     * @param Builder $query The query builder instance
     * @param mixed $value The filter value to apply
     * @return void
     */
    public function apply(Builder $query, $value): void
    {
        if ($value === '' || $value === null) {
            return;
        }

        $query->where($this->key, (bool) $value);
    }
}
