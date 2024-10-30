<?php

namespace Borek\LaravelTableBuilder\Tables\Contracts;

use Illuminate\Database\Eloquent\Builder;

/**
 * Interface for table filters.
 * 
 * Filters allow users to narrow down table data based on specific criteria.
 */
interface FilterInterface
{
    /**
     * Apply the filter's constraints to the query.
     * 
     * @param Builder $query The query builder instance
     * @param mixed $value The filter value to apply
     * @return void
     */
    public function apply(Builder $query, $value): void;

    /**
     * Get the filter's configuration array.
     * 
     * @return array{key: string, label: string, type: string, options: array}
     */
    public function getConfig(): array;

    /**
     * Get the unique key identifier for this filter.
     * 
     * @return string
     */
    public function getKey(): string;

    /**
     * Check if the filter operates on a relationship.
     * 
     * @return bool
     */
    public function isRelation(): bool;
}
