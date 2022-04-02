<?php

namespace Engine\ConfigVocabulary\Integer;

use Engine\Core\ValueTypes\StringValue;

trait Transforms
{
    public function to_string() : StringValue
    {
        $str = (string) $this->value();

        $new_value = new StringValue($str);
        $this->register_transform(__METHOD__, $new_value);
        return $new_value;
    }
}
