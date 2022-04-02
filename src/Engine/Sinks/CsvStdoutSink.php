<?php

namespace Engine\Sinks;

use Engine\Core\Sink;
use Engine\Core\TargetRow;

class CsvStdoutSink implements Sink
{
    private bool $did_output_header_row = false;

    public function send(TargetRow $record)
    {
        $data = $record->target_data();

        if (!$this->did_output_header_row) {
            fputcsv(STDOUT, array_keys($data));
            $this->did_output_header_row = true;
        }

        fputcsv(STDOUT, array_values($data));
    }
}