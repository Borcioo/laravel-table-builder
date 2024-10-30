<?php

namespace Borek\LaravelTableBuilder\Tables\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * Filter for date values.
 * 
 * Allows filtering records by exact date match.
 * Uses native database date comparison.
 */
class DateFilter extends BaseFilter
{
    /**
     * Get the filter type identifier.
     * 
     * @return string
     */
    protected static function getType(): string
    {
        return 'date';
    }

    /**
     * Create a new date filter instance.
     * 
     * @param string $key The database column or relation.column name
     * @param string $label The display label for the filter
     */
    public function __construct(
        string $key,
        string $label
    ) {
        parent::__construct($key, $label, 'date');
    }

    /**
     * Apply the filter's constraints to the query.
     * 
     * @param Builder $query The query builder instance
     * @param mixed $value The date value to filter by (Y-m-d format)
     * @return void
     */
    public function apply(Builder $query, $value): void
    {
        if ($value === '' || $value === null) {
            return;
        }

        $query->whereDate($this->key, $value);
    }
}
