<?php

namespace Borek\LaravelTableBuilder\Tables\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasSort
{
    protected array $sortableFields = [];

    public function sortable(array $columns): self
    {
        $this->sortableFields = $columns;
        return $this;
    }

    protected function isSortableField(string $column): bool
    {
        return in_array($column, $this->sortableFields);
    }

    protected function applySort(Builder $query, string $column, string $direction = 'asc'): void
    {
        if (!in_array($column, $this->getAllSortableColumns())) {
            return;
        }

        if (str_contains($column, '.')) {
            $parts = explode('.', $column);
            $relation = $parts[0];
            $field = $parts[1];

            $query->orderBy(
                $query->getModel()
                    ->belongsTo($relation)
                    ->getRelated()
                    ->qualifyColumn($field),
                $direction
            );
        } else {
            $query->orderBy($column, $direction);
        }
    }
}
