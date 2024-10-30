<?php

namespace Borek\LaravelTableBuilder\Tables\Columns;

/**
 * Column for displaying icons.
 * 
 * Can be used to show single icons or boolean states with different icons.
 */
class IconColumn extends BaseColumn
{
    protected string $type = 'icon';

    /**
     * The icon identifier to display.
     * Should be compatible with your icon system (e.g. 'heroicon-o-check').
     *
     * @var string|null
     */
    protected ?string $icon = null;

    /**
     * Whether the column should treat the value as boolean and show different icons.
     *
     * @var bool
     */
    protected bool $boolean = false;

    /**
     * Set the icon to display.
     *
     * @param string $icon The icon identifier
     * @return $this
     */
    public function icon(string $icon): self
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * Set the column to boolean mode.
     * Will display different icons for true/false values.
     *
     * @return $this
     */
    public function boolean(): self
    {
        $this->boolean = true;
        return $this;
    }

    /**
     * Get the column's configuration array.
     *
     * @return array{key: string, label: string, type: string, sortable: bool, icon: string|null, boolean: bool}
     */
    public function getConfig(): array
    {
        return array_merge(parent::getConfig(), [
            'icon' => $this->icon,
            'boolean' => $this->boolean
        ]);
    }
}
