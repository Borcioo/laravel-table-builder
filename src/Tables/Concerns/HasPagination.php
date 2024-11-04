<?php

namespace Borek\LaravelTableBuilder\Tables\Concerns;


trait HasPagination
{
    protected int $defaultPerPage = 10;
    protected array $perPageOptions = [10, 25, 50, 100];

    public function pagination(int $defaultPerPage = 10, array $perPageOptions = [10, 25, 50, 100]): self
    {
        $this->defaultPerPage = $defaultPerPage;
        $this->perPageOptions = $perPageOptions;
        return $this;
    }
}
