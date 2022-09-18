<?php

namespace Bvtterfly\ModelStateMachine\Tests\Dummy\States;

use Bvtterfly\ModelStateMachine\Attributes\AllowTransitionTo;
use Bvtterfly\ModelStateMachine\Attributes\InitialState;
use Bvtterfly\ModelStateMachine\Tests\Dummy\Actions\TestAction;

enum TestStateWithTransitionAction: string
{
    #[InitialState]
    #[AllowTransitionTo(self::B, [TestAction::class])]
    case A = 'a';

    case B = 'b';
}
