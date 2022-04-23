<?php

namespace Bvtterfly\ModelStateMachine\Exceptions;

use Bvtterfly\ModelStateMachine\Contracts\StateMachineAction;
use Bvtterfly\ModelStateMachine\Contracts\StateTransition;
use Exception;

class InvalidStateMachineConfig extends Exception
{
    public static function action(string $class): static
    {
        $actionClass = StateMachineAction::class;

        return new static("{$class} should implements {$actionClass}.");
    }

    public static function enum(string $class): static
    {
        return new static("{$class} should be an enum.");
    }

    public static function backedEnum(string $class): static
    {
        return new static("{$class} should be a backed enum.");
    }

    public static function transition(string $transition): static
    {
        $transitionClass = StateTransition::class;

        return new static("{$transition} should implements {$transitionClass}.");
    }
}
