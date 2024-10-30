<?php

namespace Borek\LaravelTableBuilder\Tables\Filters;

use Borek\LaravelTableBuilder\Tables\Contracts\FilterInterface;


/**
 * Base abstract class for all table filters.
 * 
 * Provides common functionality and configuration for filters.
 */
abstract class BaseFilter implements FilterInterface
{
    /**
     * Indicates if the filter operates on a relationship.
     * 
     * @var bool
     */
    protected bool $isRelation = false;

    /**
     * Create a new filter instance.
     * 
     * @param string $key The database column or relation.column name
     * @param string $label The display label for the filter
     * @param string $type The filter type identifier
     * @param array<string|int, string> $options Available filter options for select-type filters
     */
    public function __construct(
        protected string $key,
        protected string $label,
        protected string $type,
        protected array $options = []
    ) {
        $this->isRelation = str_contains($key, '.');
    }

    /**
     * Get the filter's unique key.
     * 
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Check if the filter operates on a relationship.
     * 
     * @return bool
     */
    public function isRelation(): bool
    {
        return $this->isRelation;
    }

    /**
     * Get the filter's configuration array.
     * 
     * @return array{key: string, label: string, type: string, options: array}
     */
    public function getConfig(): array
    {
        return [
            'key' => $this->key,
            'label' => $this->label,
            'type' => $this->type,
            'options' => $this->options,
        ];
    }

    /**
     * Get the filter type identifier.
     * 
     * @return string
     */
    abstract protected static function getType(): string;

    /**
     * Create a new instance of the filter.
     * 
     * @param string $key The database column or relation.column name
     * @param string $label The display label for the filter
     * @param array<string|int, string> $options Available filter options
     * @return static
     */
    public static function make(string $key, string $label, array $options = []): static
    {
        return new static($key, $label, static::getType(), $options);
    }
}
