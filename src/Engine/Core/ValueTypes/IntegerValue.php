<?php

namespace Engine\Core\ValueTypes;

use Engine\Core\AbstractValue;
use Engine\ConfigVocabulary\Integer\Validators;
use Engine\ConfigVocabulary\Integer\Transforms;

class IntegerValue extends AbstractValue
{
    use Transforms;
    use Validators;
}