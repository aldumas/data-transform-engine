<?php

namespace Engine\Core;

use Engine\Core\References\SourceRowReference;
use Engine\Exceptions\EngineException;

// Include all globally-namespaced value generators here, since they will not be
// autoloaded, and the config files need access to the generator functions.
require_once __DIR__ . '/../ConfigVocabulary/Date/Tags.php';

class Engine
{
    private Source $source;
    private string $config;
    private Sink $sink;

    public function __construct(Source $source, string $config, Sink $sink)
    {
        $this->source = $source;
        $this->config = $config;
        $this->sink = $sink;
    }

    public function run(): void
    {
        foreach ($this->source->rows() as $row) {
            try {
                $processed_data = include($this->config_file());
                $processed_data = self::replace_values($processed_data);

                $this->sink->send(new TargetRow($processed_data, self::row_reference_from_row($row)));
            } catch (EngineException $ex) {
                $raw_row = self::row_reference_from_row($row)->raw_row_data();
                error_log($ex->getMessage() . " ROW: $raw_row");
            }
        }
    }

    private function config_file() : string
    {
        return "config/{$this->config}.php";
    }

    private static function replace_values($arr) : array
    {
        return array_map(
            function ($v) {
                return $v instanceof Value ? $v->value() : $v;
            }, $arr);
    }

    private static function row_reference_from_row(array $row) : SourceRowReference
    {
        // Since all column values share the same SourceRowReference, use first.
        reset($row); // make sure internal array pointer points to a column.
        return current($row)->column_reference()->row_reference();
    }
}
