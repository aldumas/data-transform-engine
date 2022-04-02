<?php

namespace Cli;

class Parser
{
    /**
     * Return the arguments (everything after the options).
     *
     * @return string[]
     */
    public function args() : array
    {
        return $this->args;
    }

    /**
     * Return the name of the config file (without extension).
     *
     * This name corresponds to a file in the config directory.
     *
     * @return string
     */
    public function config() : string
    {
        return $this->config;
    }

    private function __construct(array $args, string $config)
    {
        $this->args = $args;
        $this->config = $config;
    }

    /**
     * Return a new Cli object populated with the command line arguments.
     *
     * If help was requested or there was a usage error, the usage instructions
     * will be displayed.
     *
     * @param int argc number of arguments
     * @param string[] $argv array of arguments, we index 0 is the name of
     *   the script.
     *
     * @return ?Parser null is returned if there was a usage error or the
     *   help message was requested
     */
    public static function parse_command_line(int $argc, array $argv) : ?self
    {
        try {
            $options = getopt('c:h', ['config:', 'help'], $opt_end);
            $args = array_slice($argv, $opt_end);

            if (self::is_help_requested($options)) {
                echo self::usage($argv[0]);
                return null;
            }

            $config = self::config_from_options($options);

            return new self($args, $config);
        }
        catch (CliUsageException $e) {
            echo ucfirst($e->getMessage()), ".\n";
            echo self::usage($argv[0]);
            return null;
        }
    }

    private static function is_help_requested($options) : bool
    {
        return isset($options['h']) || isset($options['help']);
    }

    private static function option_arg($long_name, $short_name, array $options) : ?string
    {
        foreach ([$long_name, $short_name] as $option_name) {
            $arg = $options[$option_name] ?? null;
            if (is_string($arg) && strlen($arg) > 0) {
                return $arg;
            }
        }
        return null;
    }

    private static function config_from_options($options) : string
    {
        $config = self::option_arg('config', 'c', $options);

        if (is_null($config))
            throw new CliUsageException('a config file is required');

        return $config;
    }

    /**
     * Return the usage instructions.
     *
     * @param string $script_name name of the script as it was invoked.
     * @return string
     */
    private static function usage(string $script_name) : string
    {
        return <<<USAGE
               Usage: php $script_name [OPTION]... INPUT_CSV_FILE
               Transform a CSV file from an input format to an output format.
               
               Example: php $script_name --config company_abc data.csv
               
               -f, --config     Name of the config file in the config directory
                                (without the .php extension)
               -h, --help       This help message
               
               USAGE;
    }

    /**
     * @var string[] arguments for the source to use to obtain data
     */
    private array $args;

    /**
     * @var string config file. This corresponds to a file in the config
     *   directory (without the php extension).
     */
    private string $config;
}