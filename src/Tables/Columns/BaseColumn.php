<?php

namespace Borek\LaravelTableBuilder\Tables\Columns;

use Borek\LaravelTableBuilder\Tables\Contracts\ColumnInterface;

/**
 * Base abstract class for all table columns.
 * 
 * Provides common functionality for column configuration and behavior.
 */
abstract class BaseColumn implements ColumnInterface
{
    /**
     * Determines if the column is sortable.
     *
     * @var bool
     */
    protected bool $sortable = false;

    /**
     * Create a new column instance.
     *
     * @param string $key The database column or accessor name
     * @param string $label The display label for the column
     * @param string $type The column type identifier
     */
    public function __construct(
        protected string $key,
        protected string $label,
        protected string $type = 'text'
    ) {}

    /**
     * Get the column's unique key.
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Get the column's configuration array.
     *
     * @return array{key: string, label: string, type: string, sortable: bool}
     */
    public function getConfig(): array
    {
        return [
            'key' => $this->key,
            'label' => $this->label,
            'type' => $this->type,
            'sortable' => $this->sortable,
        ];
    }

    /**
     * Make the column sortable or not.
     *
     * @param bool $sortable
     * @return $this
     */
    public function sortable(bool $sortable = true): self
    {
        $this->sortable = $sortable;
        return $this;
    }

    /**
     * Check if the column is sortable.
     *
     * @return bool
     */
    public function isSortable(): bool
    {
        return $this->sortable;
    }

    /**
     * Create a new instance of the column.
     *
     * @param string $key The database column or accessor name
     * @param string $label The display label for the column
     * @return static
     */
    public static function make(string $key, string $label): static
    {
        return new static($key, $label);
    }
}
