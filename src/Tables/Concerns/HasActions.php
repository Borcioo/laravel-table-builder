<?php

namespace Borek\LaravelTableBuilder\Tables\Concerns;

use Borek\LaravelTableBuilder\Tables\Contracts\ActionInterface;
use Borek\LaravelTableBuilder\Tables\Actions\ButtonAction;
use Borek\LaravelTableBuilder\Tables\Actions\LinkAction;

trait HasActions
{
    protected array $actions = [];

    public function addAction(ActionInterface $action): self
    {
        $this->actions[] = $action;
        return $this;
    }

    public function getActions(): array
    {
        return array_map(fn(ActionInterface $action) => $action->getConfig(), $this->actions);
    }

    public function buttonAction(string $name, string $label, array $config = []): self
    {
        return $this->addAction(new ButtonAction($name, $label, $config));
    }

    public function linkAction(string $name, string $label, array $config = []): self
    {
        return $this->addAction(new LinkAction($name, $label, $config));
    }

    protected function findAction(string $name): ?ActionInterface
    {
        foreach ($this->actions as $action) {
            if ($action->getName() === $name) {
                return $action;
            }
        }
        return null;
    }
}
