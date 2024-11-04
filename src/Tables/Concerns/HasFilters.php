<?php

namespace Borek\LaravelTableBuilder\Tables\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Borek\LaravelTableBuilder\Tables\Contracts\FilterInterface;
use Borek\LaravelTableBuilder\Tables\Filters\SelectFilter;
use Borek\LaravelTableBuilder\Tables\Filters\TextInputFilter;
use Borek\LaravelTableBuilder\Tables\Filters\NumberFilter;
use Borek\LaravelTableBuilder\Tables\Filters\TernaryFilter;

trait HasFilters
{
    protected array $filters = [];

    public function addFilter(FilterInterface $filter): self
    {
        $this->filters[] = $filter;
        return $this;
    }

    public function getFilters(): array
    {
        return array_map(fn(FilterInterface $filter) => $filter->getConfig(), $this->filters);
    }

    /**
     * Find a filter by its key.
     * 
     * @param string $key The filter key to find
     * @return FilterInterface|null The found filter or null
     */
    protected function findFilter(string $key): ?FilterInterface
    {
        foreach ($this->filters as $filter) {
            if ($filter->getKey() === $key) {
                return $filter;
            }
        }
        return null;
    }

    protected function applyFilters(Builder $query, array $filters): void
    {
        foreach ($filters as $key => $value) {
            $filter = $this->findFilter($key);
            if ($filter && $value !== '' && $value !== null) {
                $filter->apply($query, $value);
            }
        }
    }

    /**
     * Add a select filter to the table.
     * 
     * @param string $key The database column or relation.column name
     * @param string $label The display label for the filter
     * @param array<string|int, string> $options Available filter options as key-value pairs
     * @return $this
     */
    public function selectFilter(string $key, string $label, array $options): self
    {
        return $this->addFilter(new SelectFilter($key, $label, $options));
    }


    /**
     * Add a text input filter to the table.
     * 
     * @param string $key The database column or relation.column name
     * @param string $label The display label for the filter
     * @return $this
     */
    public function textFilter(string $key, string $label): self
    {
        return $this->addFilter(new TextInputFilter($key, $label));
    }

    /**
     * Add a number filter to the table.
     * 
     * @param string $key The database column or relation.column name
     * @param string $label The display label for the filter
     * @return $this
     */
    public function numberFilter(string $key, string $label): self
    {
        return $this->addFilter(new NumberFilter($key, $label));
    }

    /**
     * Add a ternary filter to the table.
     * 
     * @param string $key The database column or relation.column name
     * @param string $label The display label for the filter
     * @return $this
     */
    public function ternaryFilter(string $key, string $label): self
    {
        return $this->addFilter(new TernaryFilter($key, $label));
    }
}
