<?php

namespace Borek\LaravelTableBuilder\Tables\Columns;

/**
 * Column for displaying boolean values.
 * 
 * Renders boolean values as customizable labels (e.g., "Yes"/"No", "Active"/"Inactive").
 */
class BooleanColumn extends BaseColumn
{
    /**
     * The column type identifier.
     * 
     * @var string
     */
    protected string $type = 'boolean';

    /**
     * Label to display for true values.
     * 
     * @var string
     */
    protected string $trueLabel = 'Tak';

    /**
     * Label to display for false values.
     * 
     * @var string
     */
    protected string $falseLabel = 'Nie';

    /**
     * Set custom labels for true and false values.
     * 
     * @param string $trueLabel Label for true values
     * @param string $falseLabel Label for false values
     * @return $this
     */
    public function labels(string $trueLabel, string $falseLabel): self
    {
        $this->trueLabel = $trueLabel;
        $this->falseLabel = $falseLabel;
        return $this;
    }

    public function getConfig(): array
    {
        return array_merge(parent::getConfig(), [
            'trueLabel' => $this->trueLabel,
            'falseLabel' => $this->falseLabel
        ]);
    }
}
