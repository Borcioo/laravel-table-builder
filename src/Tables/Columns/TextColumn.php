<?php

namespace Borek\LaravelTableBuilder\Tables\Columns;

/**
 * Column for displaying text values.
 * 
 * Basic column type for showing text content without special formatting.
 */
class TextColumn extends BaseColumn
{
    /**
     * The column type identifier.
     * 
     * @var string
     */
    protected string $type = 'text';
}
