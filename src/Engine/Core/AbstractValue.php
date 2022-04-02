<?php

namespace Engine\Core;

use Engine\Exceptions\UnknownOperationException;

abstract class AbstractValue implements Value {

    private mixed $value;
    private ?string $previous_value_transform_name = null;
    private ?AbstractValue $previous_value = null;

    public function __construct(mixed $value)
    {
        $this->value = $value;
    }

    public function value()
    {
        return $this->value;
    }

    /**
     * Enable omission of parenthesis for no-argument methods.
     *
     * @param string $name name of validator or transformer method
     * @return AbstractValue
     */
    public function __get(string $name)
    {
        return $this->$name(); // TODO make sure this will trigger __call() if the method does not exist.
    }

    /**
     * Report attempt to call unknown operation, capturing details of where it
     * occurred while processing the source.
     *
     * @param string $name name of function being called
     * @param array $args
     * @return $this
     */
    public function __call(string $name, array $args)
    {
        throw new UnknownOperationException($this, $name, $args);
    }

    protected function register_transform($name, AbstractValue $new_value) : void
    {
        $new_value->previous_value_transform_name = $name;
        $new_value->previous_value = $this;
    }

    public function column_reference()
    {
        return is_null($this->previous_value) ? null : $this->previous_value->column_reference();
    }
}