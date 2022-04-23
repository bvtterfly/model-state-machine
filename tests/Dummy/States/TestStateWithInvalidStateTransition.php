<?php

namespace Bvtterfly\ModelStateMachine\Tests\Dummy\States;

use Bvtterfly\ModelStateMachine\Attributes\AllowTransitionTo;
use Bvtterfly\ModelStateMachine\Attributes\DefaultState;
use Bvtterfly\ModelStateMachine\Tests\Dummy\StateTransitions\InvalidStateTransition;

enum TestStateWithInvalidStateTransition: string
{
    #[DefaultState]
    #[AllowTransitionTo(self::B, transition: InvalidStateTransition::class)]
    case A = 'a';

    case B = 'b';
}
