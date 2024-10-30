<?php

namespace Borek\LaravelTableBuilder\Tables\Inputs;

use Borek\LaravelTableBuilder\Tables\Contracts\InputInterface;

/**
 * Base abstract class for all table inputs.
 * 
 * Provides common functionality for form inputs used in tables,
 * such as filters and action forms.
 */
abstract class BaseInput implements InputInterface
{
    /**
     * Create a new input instance.
     * 
     * @param string $key Input field identifier
     * @param string $label Display label for the input
     * @param string $type Input type identifier (text, number, select, etc.)
     * @param array<string, mixed> $attributes Additional HTML attributes
     */
    public function __construct(
        protected string $key,
        protected string $label,
        protected string $type,
        protected array $attributes = []
    ) {}

    /**
     * Get the input's configuration array.
     * 
     * @return array{key: string, label: string, type: string, attributes: array}
     */
    public function getConfig(): array
    {
        return [
            'key' => $this->key,
            'label' => $this->label,
            'type' => $this->type,
            'attributes' => $this->attributes,
        ];
    }

    /**
     * Add custom HTML attributes to the input.
     * 
     * @param array<string, mixed> $attributes
     * @return $this
     */
    public function attributes(array $attributes): self
    {
        $this->attributes = array_merge($this->attributes, $attributes);
        return $this;
    }

    /**
     * Get the input's unique key.
     * 
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }
}
