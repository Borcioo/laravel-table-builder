<?php

namespace Borek\LaravelTableBuilder\Tables\Contracts;

/**
 * Interface for table actions.
 * 
 * Actions are interactive elements that can be triggered by users,
 * such as buttons, links, or other clickable elements.
 */
interface ActionInterface
{
    /**
     * Get the action's configuration array.
     * 
     * @return array Configuration array containing all action settings
     */
    public function getConfig(): array;

    /**
     * Get the unique name identifier for this action.
     * 
     * @return string
     */
    public function getName(): string;
}
