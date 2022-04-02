<?php

namespace Engine\Core\References;

use Engine\Core\Util\SetPropertiesFromAssocTrait;

class SourceRowReference
{
    use SetPropertiesFromAssocTrait;

    private int $line_number;
    private int $row_number; // TODO document why this could be different than $line_number
    private string $raw_row_data;
    private array $rec;
    private string $source_name;

    public function __construct($ref_arr)
    {
        $this->set_properties_from_assoc($ref_arr);
    }

    /**
     * @return int
     */
    public function line_number(): int
    {
        return $this->line_number;
    }

    /**
     * @return int
     */
    public function row_number(): int
    {
        return $this->row_number;
    }

    /**
     * @return string
     */
    public function raw_row_data(): string
    {
        return $this->raw_row_data;
    }

    /**
     * @return array
     */
    public function rec(): array
    {
        return $this->rec;
    }

    /**
     * @return string
     */
    public function source_name(): string
    {
        return $this->source_name;
    }
}
