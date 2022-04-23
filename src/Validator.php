<?php

namespace Bvtterfly\ModelStateMachine;

use Bvtterfly\ModelStateMachine\Contracts\StateMachineAction;
use Bvtterfly\ModelStateMachine\Contracts\StateTransition;
use Bvtterfly\ModelStateMachine\Exceptions\InvalidStateMachineConfig;

class Validator
{
    public static function validateAction(string $action): void
    {
        throw_unless(is_subclass_of($action, StateMachineAction::class), InvalidStateMachineConfig::action($action));
    }

    public static function validateTransition(string $transition): void
    {
        throw_unless(is_subclass_of($transition, StateTransition::class), InvalidStateMachineConfig::transition($transition));
    }
}
