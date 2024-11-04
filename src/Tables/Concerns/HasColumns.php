<?php

namespace Borek\LaravelTableBuilder\Tables\Concerns;

use Borek\LaravelTableBuilder\Tables\Contracts\ColumnInterface;
use Borek\LaravelTableBuilder\Tables\Columns\TextColumn;
use Borek\LaravelTableBuilder\Tables\Columns\DateColumn;
use Borek\LaravelTableBuilder\Tables\Columns\IconColumn;
use Borek\LaravelTableBuilder\Tables\Columns\ImageColumn;
use Borek\LaravelTableBuilder\Tables\Columns\BadgeColumn;
use Borek\LaravelTableBuilder\Tables\Columns\BooleanColumn;

trait HasColumns
{
    protected array $columns = [];
    protected array $sortableColumns = [];

    public function addColumn(ColumnInterface $column): self
    {
        $this->columns[] = $column;

        if ($column->isSortable()) {
            $this->sortableColumns[] = $column->getKey();
        }

        return $this;
    }

    public function getColumns(): array
    {
        return array_map(fn(ColumnInterface $column) => $column->getConfig(), $this->columns);
    }

    public function getSortableColumns(): array
    {
        return $this->sortableColumns;
    }

    /**
     * Check if a column is sortable.
     * 
     * @param string $column The column key to check
     * @return bool True if the column is sortable
     */
    public function isSortable(string $column): bool
    {
        return in_array($column, $this->sortableColumns);
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
}
