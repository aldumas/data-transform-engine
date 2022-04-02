<?php

use Engine\Core\Engine;
use Engine\Exceptions\EngineException;
use Engine\Sinks\CsvStdoutSink;
use Engine\Sources\CsvFileSource;

require_once 'vendor/autoload.php';


$cli = Cli\Parser::parse_command_line($argc, $argv);

if (is_null($cli)) {
    exit(1);
}

try {
    $file = $cli->args()[0];
    $csv_fh = fopen($file, 'r');

    if ($csv_fh === false) die('Error opening CSV file');

    $source = new CsvFileSource($csv_fh, $file);
    $sink = new CsvStdoutSink();

    $engine = new Engine($source, $cli->config(), $sink);
    $engine->run();

    fclose($csv_fh);

} catch (EngineException $e) {
    echo $e->getMessage(), "\n";
}

