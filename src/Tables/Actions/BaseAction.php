<?php

namespace Borek\LaravelTableBuilder\Tables\Actions;

use Borek\LaravelTableBuilder\Tables\Contracts\ActionInterface;
use Illuminate\Support\Facades\Route;

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
     * Route parameters for the action.
     * 
     * @var array<string, mixed>
     */
    protected array $routeParameters = [];

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
            'method' => 'get',
            'icon' => null,
            'variant' => 'default',
            'size' => 'default',
            'preserveScroll' => false,
            'preserveState' => false,
            'confirm' => false,
            'confirmText' => 'Are you sure?',
            'visible' => true,
            'disabled' => false,
        ];

        $this->config = array_merge($defaultConfig, $config);
    }

    protected function actionUrl(string $route, array $parameters = []): string
    {
        return route($route, $parameters);
    }

    public function route(string $name, array $parameters = []): self
    {
        $this->config['route'] = $name;
        $this->routeParameters = $parameters;
        return $this;
    }

    public function getConfig(): array
    {
        $config = $this->config;

        if (isset($config['route'])) {
            $config['routeName'] = $config['route'];
            $config['routeParams'] = $this->routeParameters;
            unset($config['route']);
        }

        return $config;
    }

    public function getName(): string
    {
        return $this->config['name'];
    }

    public static function make(string $name, string $label, array $config = []): static
    {
        return new static($name, $label, $config);
    }
}
