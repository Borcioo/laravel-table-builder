<?php

namespace Borek\LaravelTableBuilder\Tables\Columns;

/**
 * Column for displaying badge elements.
 * 
 * Renders values as badges with configurable colors and styles.
 *
 * @method self color(string $color) Set the color of the badge
 * @method self background(string $background) Set the background color of the badge
 * @method self colors(array<string, string> $colors) Set color mapping for badge values
 * @method self backgrounds(array<string, string> $backgrounds) Set background mapping for badge values
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
     * The color of the badge.
     * 
     * @var ?string
     */
    protected ?string $color = null;

    /**
     * The background color of the badge.
     * 
     * @var ?string
     */
    protected ?string $background = null;

    /**
     * Array of color configurations for different values.
     * 
     * @var array<string, string>
     */
    protected array $colors = [];

    /**
     * Array of background configurations for different values.
     * 
     * @var array<string, string>
     */
    protected array $backgrounds = [];

    /**
     * Set the color of the badge.
     * 
     * @param string $color The color of the badge
     * @return $this
     */
    public function color(string $color): self
    {
        $this->color = $color;
        return $this;
    }

    /**
     * Set the background color of the badge.
     * 
     * @param string $background The background color of the badge
     * @return $this
     */
    public function background(string $background): self
    {
        $this->background = $background;
        return $this;
    }

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
     * Set the background mapping for badge values.
     * 
     * @param array<string, string> $backgrounds Array of value => background mappings
     * @return $this
     * 
     * @example
     * ```php
     * ->backgrounds([
     *     'active' => 'green',
     *     'inactive' => 'gray',
     *     'pending' => 'yellow'
     * ])
     * ```
     */
    public function backgrounds(array $backgrounds): self
    {
        $this->backgrounds = $backgrounds;
        return $this;
    }

    /**
     * Get the column's configuration array.
     * 
     * @return array{key: string, label: string, type: string, sortable: bool, color: ?string, background: ?string, colors: array, backgrounds: array}
     */
    public function getConfig(): array
    {
        return array_merge(parent::getConfig(), [
            'color' => $this->color,
            'background' => $this->background,
            'colors' => $this->colors,
            'backgrounds' => $this->backgrounds
        ]);
    }

    public function __construct(string $key, string $label)
    {
        parent::__construct($key, $label, 'badge');
    }
}
