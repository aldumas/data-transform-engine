<?php

namespace Engine\ConfigVocabulary\Integer;


use Engine\Core\AbstractValue;
use Engine\Core\ValueTypes\IntegerValue;
use Engine\Exceptions\ValueException;

trait Validators
{
    public function is_month_number() : IntegerValue
    {
        $val = $this->value();
        if (1 <= $val && $val <= 12)
            return $this;

        throw new ValueException($this, 'Integer is not a valid month number');
    }

    public function is_day_of_month_number() : IntegerValue
    {
        $val = $this->value();
        if (1 <= $val && $val <= 31)
            return $this;

        throw new ValueException($this, 'Integer is not a valid day of month number');
    }

    public function is_between(int $first, int $last) : IntegerValue
    {
        if ( ! ($first <= $this->value() && $this->value() <= $last) ) {
            throw new ValueException($this, "Integer is not in the range [$first, $last]");
        }
        return $this;
    }
}