<?php

namespace Engine\ConfigVocabulary\String;

use Engine\Core\ValueTypes\StringValue;
use Engine\Exceptions\ValueException;

trait Validators
{
    public function has_length(int $len): StringValue
    {
        if (strlen($this->value()) !== $len)
            throw new ValueException($this, "String does not have length $len");

        return $this;
    }

    public function has_length_between(int $min, int $max): StringValue
    {
        $len = strlen($this->value());
        if ($min <= $len && $len <= $max)
            return $this;

        throw new ValueException($this, "String does not have length between $min and $max characters");
    }

    public function is_digits() : StringValue
    {
        if (! preg_match('/^\d+$/', $this->value())) {
            throw new ValueException($this, 'String contains non-digit characters or is empty');
        }
        return $this;
    }

    public function is_alphanumeric() : StringValue
    {
        if (! preg_match('/^[\dA-Za-z]+$/', $this->value())) {
            throw new ValueException($this, 'String is not alphanumeric');
        }
        return $this;
    }

    public function is_alphabetic() : StringValue
    {
        if (! preg_match('/^[A-Za-z]+$/', $this->value())) {
            throw new ValueException($this, 'String is not alphabetic');
        }
        return $this;
    }

    public function is_decimal() : StringValue
    {
        // #,###0.0#
        $val = str_replace(',', '', $this->value());

        if (! preg_match('/^\d+\.\d+$/', $val)) {
            throw new ValueException($this, 'String is not a decimal');
        }

        return $this;
    }
}