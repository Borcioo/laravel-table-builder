<?php

namespace Borek\LaravelTableBuilder\Tables\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * Filter for three-state boolean values.
 * 
 * Allows filtering by true/false/all values with customizable labels.
 */
class TernaryFilter extends BaseFilter
{
    /**
     * Label for true values.
     * 
     * @var string
     */
    protected string $trueLabel = 'Tak';

    /**
     * Label for false values.
     * 
     * @var string
     */
    protected string $falseLabel = 'Nie';

    /**
     * Label for "all" option.
     * 
     * @var string
     */
    protected string $allLabel = 'Wszystkie';

    /**
     * Get the filter type identifier.
     * 
     * @return string
     */
    protected static function getType(): string
    {
        return 'ternary';
    }

    /**
     * Create a new ternary filter instance.
     * 
     * @param string $key The database column or relation.column name
     * @param string $label The display label for the filter
     */
    public function __construct(
        string $key,
        string $label
    ) {
        parent::__construct($key, $label, 'ternary', [
            '' => 'Wszystkie',
            '1' => 'Tak',
            '0' => 'Nie'
        ]);
    }

    /**
     * Set custom labels for the filter options.
     * 
     * @param string $trueLabel Label for true values
     * @param string $falseLabel Label for false values
     * @param string $allLabel Label for "all" option
     * @return $this
     */
    public function labels(string $trueLabel, string $falseLabel, string $allLabel): self
    {
        $this->trueLabel = $trueLabel;
        $this->falseLabel = $falseLabel;
        $this->allLabel = $allLabel;
        $this->options = [
            '' => $allLabel,
            '1' => $trueLabel,
            '0' => $falseLabel
        ];
        return $this;
    }

    /**
     * Apply the filter's constraints to the query.
     * 
     * @param Builder $query The query builder instance
     * @param mixed $value The filter value to apply
     * @return void
     */
    public function apply(Builder $query, $value): void
    {
        if ($value === '' || $value === null) {
            return;
        }

        $query->where($this->key, (bool) $value);
    }
}
