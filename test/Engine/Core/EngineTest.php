<?php

namespace Test\Engine\Core;


use Engine\Core\Engine;
use Engine\Core\References\SourceColumnReference;
use Engine\Core\References\SourceRowReference;
use Engine\Core\Sink;
use Engine\Core\Source;
use Engine\Core\TargetRow;
use Engine\Core\ValueTypes\SourceValue;
use PHPUnit\Framework\TestCase;

class TestSource implements Source
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function name() : string { return __CLASS__; }
    public function rows() : iterable
    {
        foreach ($this->data as $i => $row_data) {
            yield $this->prepare_row_with_references($row_data, $i);
        }
    }

    private function prepare_row_with_references($row_data, $line_number) : array
    {
        $row_ref = new SourceRowReference([
            'line_number' => $line_number,
            'row_number' => $line_number * 2, // we'll define row numbers to always be twice the line numbers for testing
            'raw_row_data' => implode(',', $row_data),
            'rec' => $row_data,
            'source_name' => $this->name()
        ]);

        $row = [];

        foreach ($row_data as $col => $val) {
            $ref = new SourceColumnReference($col, $row_ref);
            $row[$col] = new SourceValue($val, $ref);
        }

        return $row;
    }
}


class TestSink implements Sink
{
    private array $sinked = [];

    public function send(TargetRow $record)
    {
        $this->sinked[] = $record;
    }

    public function sinked() : array { return $this->sinked; }
}


class EngineTest extends TestCase
{
    function testEngineTransformsCorrectlyFormattedData() : void
    {
        $data = [
            ['Order Number'=>'1234', 'Year'=>'2020', 'Month'=>'03', 'Day'=>'12', 'Product Number'=>'ABC123', 'Product Name'=>'FIRST', 'Count'=>'1,234.56'],
            ['Order Number'=>'56789', 'Year'=>'2021', 'Month'=>'12', 'Day'=>'31', 'Product Number'=>'456DEF', 'Product Name'=>'SECOND', 'Count'=>'0.5'],
        ];

        $expected = [
            [
                'OrderID' => 1234,
                'OrderDate' => '2020-03-12',
                'ProductId' => 'ABC123',
                'ProductName' => 'First',
                'Quantity' => '1234.56',
                'Unit' => 'kg'
            ],
            [
                'OrderID' => 56789,
                'OrderDate' => '2021-12-31',
                'ProductId' => '456DEF',
                'ProductName' => 'Second',
                'Quantity' => '0.5',
                'Unit' => 'kg'
            ]
        ];

        $source = new TestSource($data);
        $sink = new TestSink();

        $engine = new Engine($source, 'sample', $sink);

        $engine->run();

        $actual = array_map(function($r){return $r->target_data();}, $sink->sinked());
        $this->assertEquals($expected, $actual);
    }

    function testSecondRowStillProcessesAfterErrorOnFirstRow() : void
    {
        $data = [
            ['Order Number'=>'1234', 'Year'=>'202020202020', 'Month'=>'03', 'Day'=>'12', 'Product Number'=>'ABC123', 'Product Name'=>'FIRST', 'Count'=>'1,234.56'],
            ['Order Number'=>'56789', 'Year'=>'2021', 'Month'=>'12', 'Day'=>'31', 'Product Number'=>'456DEF', 'Product Name'=>'SECOND', 'Count'=>'0.5'],
        ];

        $expected = [
            [
                'OrderID' => 56789,
                'OrderDate' => '2021-12-31',
                'ProductId' => '456DEF',
                'ProductName' => 'Second',
                'Quantity' => '0.5',
                'Unit' => 'kg'
            ]
        ];

        $source = new TestSource($data);
        $sink = new TestSink();

        $engine = new Engine($source, 'sample', $sink);
        $engine->run(); // TODO inject a logger so we can test the log messages

        $actual = array_map(function($r){return $r->target_data();}, $sink->sinked());
        $this->assertEquals($expected, $actual);
    }
}
