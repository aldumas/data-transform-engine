<?php

return [
    'OrderID' => $row ['Order Number'] -> is_digits -> to_integer,
    'OrderDate' => yyyy_mm_dd
    (
        $row ['Year'] -> has_length (4) -> is_digits -> to_integer,
        $row ['Month'] -> has_length (2) -> is_digits -> to_integer -> is_month_number,
        $row ['Day']  -> has_length (2) -> is_digits -> to_integer -> is_day_of_month_number
    ),
    'ProductId' => $row ['Product Number'] -> is_alphanumeric,
    'ProductName' => $row ['Product Name'] -> is_alphabetic -> to_proper_case,
    'Quantity' => $row ['Count'] -> is_decimal -> to_decimal -> is_between('0.0', '9999.99'),
    'Unit' => 'kg'
];
