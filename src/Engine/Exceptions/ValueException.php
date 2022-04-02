<?php

namespace Engine\Exceptions;


use Engine\Core\Value;

/**
 * An error with the content of a column in the source data.
 *
 * @todo need a separate class for errors that stem from the contents of a combination of columns.
 */
class ValueException extends EngineException
{
    public function __construct(Value $value, string $message, ?\Throwable $previous = null)
    {
        $col_ref = $value->column_reference();
        if ($col_ref) {
            $row_ref = $col_ref->row_reference();
            $message .= " while processing column '{$col_ref->column_name()}' at line {$row_ref->line_number()} of {$row_ref->source_name()}";
        }

        parent::__construct($message, $previous);
    }
}
