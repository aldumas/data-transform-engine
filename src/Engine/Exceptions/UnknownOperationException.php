<?php

namespace Engine\Exceptions;


use Engine\Core\Value;

class UnknownOperationException extends ValueException
{
    public function __construct(Value $value, string $operation_name, ?array $args, ?\Throwable $previous = null)
    {
        parent::__construct($value, self::create_message($operation_name, $args), $previous);
    }

    private static function create_message($operation_name, $args) : string
    {
        $message = "Encountered unknown operation '$operation_name'";

        if ($args) $message .= ' with args ' . strtr(var_export($args, true), "\n", ' ');

        return $message;
    }
}
