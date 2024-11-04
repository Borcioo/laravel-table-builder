<?php

namespace Borek\LaravelTableBuilder\Tables\Actions;

class LinkAction extends BaseAction
{
    public function __construct(
        string $name,
        string $label,
        array $config = []
    ) {
        $defaultConfig = [
            'type' => 'link',
            'method' => 'get',
            'variant' => 'link',
            'size' => 'default',
        ];

        parent::__construct($name, $label, array_merge($defaultConfig, $config));
    }
}
