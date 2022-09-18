<?php

namespace Bvtterfly\ModelStateMachine\Tests\Dummy\States;

use Bvtterfly\ModelStateMachine\Attributes\AllowTransitionTo;
use Bvtterfly\ModelStateMachine\Attributes\InitialState;
use Bvtterfly\ModelStateMachine\Tests\Dummy\StateTransitions\CustomStateTransition;

enum TestStateWithStateTransition: string
{
    #[InitialState]
    #[AllowTransitionTo(self::B, transition: CustomStateTransition::class)]
    case A = 'a';

    case B = 'b';
}
