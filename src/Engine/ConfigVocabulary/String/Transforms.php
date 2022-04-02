<?php

namespace Engine\ConfigVocabulary\String;

use Engine\Core\ValueTypes\DecimalValue;
use Engine\Core\ValueTypes\IntegerValue;
use Engine\Core\ValueTypes\StringValue;

trait Transforms
{
    public function to_integer(): IntegerValue
    {
        $new_value = new IntegerValue(intval($this->value()));
        $this->register_transform(__METHOD__, $new_value);
        return $new_value;
    }

    public function to_trimmed() : StringValue
    {
        $new_value = new StringValue(trim($this->value()));
        $this->register_transform(__METHOD__, $new_value);
        return $new_value;
    }

    public function to_proper_case() : StringValue
    {
        $new_value = new StringValue(ucwords(strtolower($this->value())));
        $this->register_transform(__METHOD__, $new_value);
        return $new_value;
    }

    public function to_decimal() : DecimalValue
    {
        $val = str_replace(',', '', $this->value());

        $new_value = new DecimalValue($val);
        $this->register_transform(__METHOD__, $new_value);
        return $new_value;
    }
}
