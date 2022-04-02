<?php

namespace Engine\Exceptions;


use Engine\Core\Value;

/**
 * An error with the content of a combination of multiple columns in the source
 * data.
 */
class MultiValueException extends EngineException
{
    public function __construct(array $values, string $message, ?\Throwable $previous = null)
    {
        $columns = implode(', ', array_map(function($v) { return "'{$v->column_reference()->column()}'"; }, $values));
        $ref = $values[0]->column_reference()->row_reference();

        if ($ref) $message .= " while processing columns $columns at line {$ref->line()} of {$ref->source_name()}";

        parent::__construct($message, $previous);
    }
}
