<?php

namespace Borek\LaravelTableBuilder\Tables\Columns;

/**
 * Column for displaying badge elements.
 * 
 * Renders values as badges with configurable colors and styles.
 */
class BadgeColumn extends BaseColumn
{
    /**
     * The column type identifier.
     * 
     * @var string
     */
    protected string $type = 'badge';

    /**
     * Array of color configurations for different values.
     * 
     * @var array<string, string>
     */
    protected array $colors = [];

    /**
     * Set the color mapping for badge values.
     * 
     * @param array<string, string> $colors Array of value => color mappings
     * @return $this
     * 
     * @example
     * ```php
     * ->colors([
     *     'active' => 'green',
     *     'inactive' => 'gray',
     *     'pending' => 'yellow'
     * ])
     * ```
     */
    public function colors(array $colors): self
    {
        $this->colors = $colors;
        return $this;
    }

    /**
     * Get the column's configuration array.
     * 
     * @return array{key: string, label: string, type: string, sortable: bool, colors: array}
     */
    public function getConfig(): array
    {
        return array_merge(parent::getConfig(), [
            'colors' => $this->colors
        ]);
    }
}
