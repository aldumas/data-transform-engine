<?php

namespace Engine\Sources;

use Engine\Core\References\SourceColumnReference;
use Engine\Core\References\SourceRowReference;
use Engine\Core\Source;
use Engine\Core\ValueTypes\SourceValue;

class ExampleSource implements Source
{
    function rows(): iterable
    {
        $data = [
            ['YEAR'=>'2011', 'DAY'=>'01', 'MON'=>'12', 'NAME' => 'pop can'],
            ['YEAR'=>'2011', 'DAY'=>'11', 'MON'=>'05', 'NAME' => 'sausage'],
            ['YEAR'=>'2012', 'DAY'=>'06', 'MON'=>'11', 'NAME' => 'cream cheese'],
        ];

        for ($i = 0; $i < count($data); ++$i) {
            $line = $i + 1; // let's make it 1-based.
            $row = $line; // this would be different if there were empty lines
                          // in the document, for example.

            $row_ref = new SourceRowReference([
                'line_number' => $i + 1, // let's make it 1-based
                'row_number'  => $i + 1,
                'raw_row_data'  => implode(',', $data[$i]),
                'rec'  => $data[$i],
                'source_name' => 'example.dat'
            ]);

            yield self::build_row($data[$i], $row_ref);
        }
    }

    private static function build_row($assoc, SourceRowReference $row_ref) : array
    {
        $out_assoc = [];

        foreach ($assoc as $col => $val) {
            $ref = new SourceColumnReference($col, $row_ref);
            $out_assoc[$col] = new SourceValue($val, $ref);
        }

        return $out_assoc;
    }

    public function name() : string { return 'Example source'; }
}
