<?php

namespace Engine\Core\ValueTypes;


use Engine\Core\References\SourceColumnReference;

/**
 * A value obtained from the source document.
 *
 * All values from source documents are initially represented as string values,
 * but not all string values are obtained from a (single) source value.
 */
class SourceValue extends StringValue {
    private SourceColumnReference $reference;

    public function __construct(string $value, SourceColumnReference $reference)
    {
        parent::__construct($value);
        $this->reference = $reference;
    }

    public function column_reference() : SourceColumnReference
    {
        return $this->reference;
    }
}
