<?php

/* This file needs to be in the global namespace so that the format files will
 * find these functions, since the format files run in the global namespace.
 * This is not the case for the value transforms and validators, which operate
 * off Value objects.
 */

use Engine\Core\ValueTypes\DateValue;
use Engine\Core\ValueTypes\IntegerValue;
use Engine\Exceptions\MultiValueException;

function yyyy_mm_dd(IntegerValue $year, IntegerValue $month, IntegerValue $day): DateValue
{
    $str = sprintf('%04d-%02d-%02d', $year->value(), $month->value(), $day->value());
    try {
        $date_obj = new \DateTime($str); // if this throws, it's an invalid date

        return new DateValue($str); // storing the string representation
    }
    catch (\Exception $e) {
        throw new MultiValueException([$year, $month, $day], 'Error creating DateTime object', $e);
    }
}
