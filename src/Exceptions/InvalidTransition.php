<?php

namespace Bvtterfly\ModelStateMachine\Exceptions;

use Exception;

class InvalidTransition extends Exception
{
    public static function make(string $sourceState, string $destinationState): static
    {
        return new static('The "'.$sourceState.'" state is not allowed to transit to "'.$destinationState.'" state.');
    }
}
