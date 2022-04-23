<?php

namespace Bvtterfly\ModelStateMachine\Tests\Dummy\States;

use Bvtterfly\ModelStateMachine\Attributes\AllowTransitionTo;
use Bvtterfly\ModelStateMachine\Attributes\DefaultState;
use Bvtterfly\ModelStateMachine\Tests\Dummy\StateTransitions\CustomStateTransition;

enum TestStateWithStateTransition: string
{
    #[DefaultState]
    #[AllowTransitionTo(self::B, transition: CustomStateTransition::class)]
    case A = 'a';

    case B = 'b';
}
