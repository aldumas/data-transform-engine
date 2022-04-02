<?php

namespace Engine\Sinks;

use Engine\Core\Sink;
use Engine\Core\TargetRow;

class LoggerSink implements Sink
{
    function send(TargetRow $record)
    {
        error_log(var_export($record, true));
    }
}