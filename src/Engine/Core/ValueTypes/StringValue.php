<?php

namespace Engine\Core\ValueTypes;

use Engine\Core\AbstractValue;
use Engine\ConfigVocabulary\String\Validators;
use Engine\ConfigVocabulary\String\Transforms;

class StringValue extends AbstractValue
{
    use Transforms;
    use Validators;
}
