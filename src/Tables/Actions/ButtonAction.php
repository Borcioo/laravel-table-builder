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
            'icon' => null,
            'route' => null,
            'routeParams' => [],
            'url' => null,
            'ziggy' => false // flaga określająca czy używamy Ziggy
        ];

        // Jeśli podano route, ale nie url, zakładamy że to trasa Ziggy
        if (!empty($config['route']) && empty($config['url'])) {
            $config['ziggy'] = true;
        }

        parent::__construct($name, $label, array_merge($defaultConfig, $config));
    }
}
