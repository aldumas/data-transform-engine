<?php

namespace Engine\Exceptions;


class EngineException extends \RuntimeException {
    public function __construct(string $message = "", ?\Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}