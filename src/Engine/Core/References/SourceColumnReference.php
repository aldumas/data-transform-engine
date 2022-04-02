<?php

namespace Engine\Core\References;

class SourceColumnReference
{
    private string $column_name;
    private SourceRowReference $row_reference;

    public function __construct(string $column_name, SourceRowReference $row_reference)
    {
        $this->column_name = $column_name;
        $this->row_reference = $row_reference;
    }

    public function column_name() : string
    {
        return $this->column_name;
    }

    public function row_reference() : SourceRowReference
    {
        return $this->row_reference;
    }
}
