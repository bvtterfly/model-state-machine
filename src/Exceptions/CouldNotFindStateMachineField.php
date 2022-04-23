<?php

namespace Bvtterfly\ModelStateMachine\Exceptions;

use Exception;

class CouldNotFindStateMachineField extends Exception
{
    public static function make(string $field): static
    {
        return new static("\"{$field}\" isn't a state machine field.");
    }
}
