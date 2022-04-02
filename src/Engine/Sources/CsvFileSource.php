<?php /** @noinspection PhpMissingFieldTypeInspection */

namespace Engine\Sources;

use Engine\Core\References\SourceColumnReference;
use Engine\Core\References\SourceRowReference;
use Engine\Core\Source;
use Engine\Core\ValueTypes\SourceValue;

class CsvFileSource implements Source
{
    private $csv_fh;
    private string $name;
    private ?array $headers = null;

    public function __construct($csv_fh, $name)
    {
        $this->csv_fh = $csv_fh;
        $this->name = $name;
    }


    function rows(): iterable
    {
        $line_number = $row_number = 1;

        while (true) {

            if (is_null($this->headers))
                $this->headers = fgetcsv($this->csv_fh);

            $row_info = $this->next_row($line_number);

            if ($row_info === false) // done with file
                return;

            [$line_number, $row_data] = $row_info;

            $rec = array_combine($this->headers, $row_data);

            $row_ref = new SourceRowReference([
                'line_number' => $line_number,
                'row_number' => $row_number,
                'raw_row_data' => implode(',', $row_data), // TODO ideally this would be the unparsed string
                'rec' => $rec,
                'source_name' => $this->name()
            ]);

            $row = [];

            foreach ($rec as $col => $val) {
                $ref = new SourceColumnReference($col, $row_ref);
                $row[$col] = new SourceValue($val, $ref);
            }

            yield $row;

            ++$row_number;
            ++$line_number;
        }
    }

    private function next_row($line_number) : bool|array
    {
        $row_data = null;

        while (is_null($row_data)) {
            $row_data = fgetcsv($this->csv_fh);
            if ($row_data === false) return false; // no more data in file
            if (self::is_blank_row($row_data))
                $row_data = null;
            ++$line_number;
        }

        return [$line_number, $row_data];
    }

    private static function is_blank_row($row_data) : bool
    {
        return count($row_data) === 1 and is_null($row_data[0]);
    }

    public function name() : string
    {
        return $this->name;
    }
}
