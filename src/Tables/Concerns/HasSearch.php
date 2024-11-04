<?php

namespace Borek\LaravelTableBuilder\Tables\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasSearch
{
    protected array $searchable = [];

    public function searchable(array $columns): self
    {
        $this->searchable = $columns;
        return $this;
    }

    public function getSearchableColumns(): array
    {
        return $this->searchable;
    }

    protected function applySearch(Builder $query, string $search): void
    {
        $query->where(function (Builder $query) use ($search) {
            foreach ($this->searchable as $column) {
                if (str_contains($column, '.')) {
                    $parts = explode('.', $column);
                    $relation = $parts[0];
                    $field = $parts[1];

                    $query->orWhereHas($relation, function ($query) use ($field, $search) {
                        $query->where($field, 'like', "%{$search}%");
                    });
                } else {
                    $query->orWhere($column, 'like', "%{$search}%");
                }
            }
        });
    }
}
