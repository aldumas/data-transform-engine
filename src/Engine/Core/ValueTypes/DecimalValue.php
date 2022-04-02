<?php

namespace Engine\Core\ValueTypes;

use Engine\Core\AbstractValue;
use Engine\ConfigVocabulary\Decimal\Validators;

class DecimalValue extends AbstractValue
{
    use Validators;
}
