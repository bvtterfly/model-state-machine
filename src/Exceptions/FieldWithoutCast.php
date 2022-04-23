<?php

namespace Bvtterfly\ModelStateMachine\Exceptions;

use Exception;

class FieldWithoutCast extends Exception
{
    public static function make(string $field): static
    {
        return new static("\"{$field}\" attribute doesn't have a cast.");
    }
}
