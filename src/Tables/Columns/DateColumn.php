<?php

namespace Borek\LaravelTableBuilder\Tables\Columns;

/**
 * Column for displaying date and datetime values.
 * 
 * Handles formatting and display of date/datetime values in the table.
 */
class DateColumn extends BaseColumn
{
    /**
     * The column type identifier.
     * 
     * @var string
     */
    protected string $type = 'date';

    /**
     * The date format to use for display.
     * 
     * @var string
     */
    protected string $format = 'Y-m-d';

    /**
     * Set the date format.
     * 
     * @param string $format PHP date format string
     * @return $this
     */
    public function format(string $format): self
    {
        $this->format = $format;
        return $this;
    }

    /**
     * Get the column's configuration array.
     * 
     * @return array{key: string, label: string, type: string, sortable: bool, format: string}
     */
    public function getConfig(): array
    {
        return array_merge(parent::getConfig(), [
            'format' => $this->format
        ]);
    }
}
