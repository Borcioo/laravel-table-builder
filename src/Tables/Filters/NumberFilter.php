<?php

namespace Borek\LaravelTableBuilder\Tables\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * Filter for numeric input values.
 * 
 * Allows filtering by numeric values with configurable comparison operators.
 * Supports both direct column and relationship filtering.
 */
class NumberFilter extends BaseFilter
{
    /**
     * The comparison operator to use.
     * 
     * @var string
     */
    protected string $operator = '=';

    /**
     * Get the filter type identifier.
     * 
     * @return string
     */
    protected static function getType(): string
    {
        return 'number';
    }

    /**
     * Create a new number filter instance.
     * 
     * @param string $key The database column or relation.column name
     * @param string $label The display label for the filter
     */
    public function __construct(
        string $key,
        string $label
    ) {
        parent::__construct($key, $label, 'number');
    }

    /**
     * Set the comparison operator.
     * 
     * @param string $operator One of: =, >, <, >=, <=, <>
     * @return $this
     */
    public function operator(string $operator): self
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * Apply the filter's constraints to the query.
     * 
     * @param Builder $query The query builder instance
     * @param mixed $value The numeric value to compare against
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
                $query->where($column, $this->operator, $value);
            });
        } else {
            $query->where($this->key, $this->operator, $value);
        }
    }
}
