<?php

namespace Borek\LaravelTableBuilder\Tables\Concerns;

trait HasTransformers
{
    protected array $transformers = [];

    public function transform(string|array $keys, callable $transformer): self
    {
        $keys = is_array($keys) ? $keys : [$keys];

        foreach ($keys as $key) {
            $this->transformers[$key] = $transformer;
        }

        return $this;
    }

    protected function transformData(array $items): array
    {
        return array_map(function ($item) {
            $transformed = [];

            foreach ($this->columns as $column) {
                $key = $column->getKey();
                $transformed[$key] = $this->transformValue($item, $key);
            }

            return $transformed;
        }, $items);
    }

    protected function transformValue($item, string $key): mixed
    {
        if (isset($this->transformers[$key])) {
            return ($this->transformers[$key])($item);
        }

        return data_get($item, $key);
    }
}
