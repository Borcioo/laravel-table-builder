<?php

namespace Borek\LaravelTableBuilder\Tables\Contracts;

/**
 * Interface for table inputs.
 * 
 * Defines contract for form inputs used in tables,
 * such as filters and action forms.
 */
interface InputInterface
{
    /**
     * Get the input's configuration array.
     * 
     * @return array{key: string, label: string, type: string, attributes: array}
     */
    public function getConfig(): array;

    /**
     * Get the input's unique key.
     * 
     * @return string
     */
    public function getKey(): string;

    /**
     * Add custom HTML attributes to the input.
     * 
     * @param array<string, mixed> $attributes
     * @return $this
     */
    public function attributes(array $attributes): self;
}
