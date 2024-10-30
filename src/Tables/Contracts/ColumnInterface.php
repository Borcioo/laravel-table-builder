<?php

namespace Borek\LaravelTableBuilder\Tables\Contracts;

/**
 * Interface for table columns.
 * 
 * Columns define how data should be displayed in the table,
 * including formatting, styling, and behavior.
 */
interface ColumnInterface
{
    /**
     * Get the column's configuration array.
     * 
     * @return array Configuration array containing all column settings
     */
    public function getConfig(): array;

    /**
     * Get the unique key identifier for this column.
     * 
     * @return string
     */
    public function getKey(): string;

    /**
     * Make the column sortable or not.
     * 
     * @param bool $sortable Whether the column should be sortable
     * @return $this
     */
    public function sortable(bool $sortable = true): self;

    /**
     * Check if the column is sortable.
     * 
     * @return bool
     */
    public function isSortable(): bool;
}
