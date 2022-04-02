<?php

namespace Engine\ConfigVocabulary\Decimal;

use Engine\Core\ValueTypes\DecimalValue;
use Engine\Exceptions\ValueException;

trait Validators
{
    public function is_between(string $min, string $max): DecimalValue
    {
        $fmin = floatval($min);
        $fmax = floatval($max);
        $fval = floatval($this->value());

        if ($fmin <= $fval && $fval <= $fmax)
            return $this;

        throw new ValueException($this, "Decimal is not between $min and $max");
    }
}
