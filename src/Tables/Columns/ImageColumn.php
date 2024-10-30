<?php

namespace Borek\LaravelTableBuilder\Tables\Columns;

/**
 * Column for displaying images.
 * 
 * Supports configurable dimensions and circular/rounded display options.
 */
class ImageColumn extends BaseColumn
{
    /**
     * The column type identifier.
     * 
     * @var string
     */
    protected string $type = 'image';

    /**
     * Image width in pixels.
     * 
     * @var int|null
     */
    protected ?int $width = null;

    /**
     * Image height in pixels.
     * 
     * @var int|null
     */
    protected ?int $height = null;

    /**
     * Whether to display the image in circular shape.
     * 
     * @var bool
     */
    protected bool $circular = false;

    /**
     * Set the image width.
     * 
     * @param int $width Width in pixels
     * @return $this
     */
    public function width(int $width): self
    {
        $this->width = $width;
        return $this;
    }

    /**
     * Set the image height.
     * 
     * @param int $height Height in pixels
     * @return $this
     */
    public function height(int $height): self
    {
        $this->height = $height;
        return $this;
    }

    /**
     * Make the image display as circular.
     * 
     * @return $this
     */
    public function circular(): self
    {
        $this->circular = true;
        return $this;
    }

    /**
     * Get the column's configuration array.
     * 
     * @return array{key: string, label: string, type: string, sortable: bool, width: int|null, height: int|null, circular: bool}
     */
    public function getConfig(): array
    {
        return array_merge(parent::getConfig(), [
            'width' => $this->width,
            'height' => $this->height,
            'circular' => $this->circular
        ]);
    }
}
