<?php

namespace Borek\LaravelTableBuilder\Tables\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * Filter for text input search.
 * 
 * Allows filtering by partial text match using LIKE operator.
 * Supports both direct column and relationship filtering.
 */
class TextInputFilter extends BaseFilter
{
    /**
     * Get the filter type identifier.
     * 
     * @return string
     */
    protected static function getType(): string
    {
        return 'text';
    }

    /**
     * Create a new text input filter instance.
     * 
     * @param string $key The database column or relation.column name
     * @param string $label The display label for the filter
     */
    public function __construct(
        string $key,
        string $label
    ) {
        parent::__construct($key, $label, 'text');
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

        if ($this->isRelation()) {
            $parts = explode('.', $this->key);
            $relation = $parts[0];
            $column = $parts[1];

            $query->whereHas($relation, function ($query) use ($column, $value) {
                $query->where($column, 'like', "%{$value}%");
            });
        } else {
            $query->where($this->key, 'like', "%{$value}%");
        }
    }
}
