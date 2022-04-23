<?php

namespace Bvtterfly\ModelStateMachine\Exceptions;

use Exception;

class UnknownState extends Exception
{
    public static function make(): static
    {
        return new static("the state field is null or is unknown");
    }
}
