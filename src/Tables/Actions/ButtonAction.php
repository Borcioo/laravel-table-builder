<?php

namespace Borek\LaravelTableBuilder\Tables\Actions;

class ButtonAction extends BaseAction
{
    public function __construct(
        string $name,
        string $label,
        array $config = []
    ) {
        $defaultConfig = [
            'type' => 'button',
            'method' => 'get',
            'variant' => 'default',
            'size' => 'default',
        ];

        parent::__construct($name, $label, array_merge($defaultConfig, $config));
    }
}
