<?php

namespace Engine\Core;

interface Sink
{
    function send(TargetRow $record);
}