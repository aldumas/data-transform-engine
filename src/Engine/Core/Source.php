<?php

namespace Engine\Core;

interface Source
{
    /**
     * Return rows of the source data.
     *
     * Each row returned should be an associative array with keys corresponding
     * to the columns in the source data. The values of these keys should be
     * SourceValue objects that wrap the string data obtained from the source
     * column. Each SourceValue contains reference information on where the
     * value was obtained.
     *
     * @return iterable
     */
    function rows() : iterable; // yields SourceRecord objects

    /**
     * Return the name of the source.
     *
     * @return string
     */
    function name() : string;
}