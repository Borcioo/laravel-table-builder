<?php

namespace Borek\LaravelTableBuilder\Tables\Actions;

use Borek\LaravelTableBuilder\Tables\Contracts\ActionInterface;

/**
 * Base abstract class for all table actions.
 * 
 * Provides common functionality and configuration for actions.
 */
abstract class BaseAction implements ActionInterface
{
    /**
     * Action configuration array.
     * 
     * @var array<string, mixed>
     */
    protected array $config;

    /**
     * Create a new action instance.
     * 
     * @param string $name Unique identifier for the action
     * @param string $label Display label for the action
     * @param array<string, mixed> $config Additional configuration options
     */
    public function __construct(string $name, string $label, array $config = [])
    {
        $defaultConfig = [
            'name' => $name,
            'label' => $label,
            'type' => 'button',
            'href' => null,
            'route' => null,
            'routeParams' => [],
            'method' => 'get',
            'icon' => null,
            'variant' => 'default',
            'size' => 'default',
            'preserveScroll' => false,
            'preserveState' => false,
            'replace' => false,
            'only' => [],
            'confirm' => false,
            'confirmText' => 'Are you sure?',
            'permissions' => [],
            'visible' => true,
            'disabled' => false,
        ];

        $this->config = array_merge($defaultConfig, $config);
    }

    public function getName(): string
    {
        return $this->config['name'];
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public static function make(string $name, string $label, array $config = []): static
    {
        return new static($name, $label, $config);
    }
}
