<?php

namespace Engine\Core;

use Engine\Core\References\SourceRowReference;

class TargetRow
{
    private array $target_data;
    private SourceRowReference $source_row_reference;

    public function __construct($target_data, SourceRowReference $ref)
    {
        $this->target_data = $target_data;
        $this->source_row_reference = $ref;
    }

    /**
     * @return array
     */
    public function target_data(): array
    {
        return $this->target_data;
    }

    /**
     * @return SourceRowReference
     */
    public function source_row_reference(): SourceRowReference
    {
        return $this->source_row_reference;
    }
}
