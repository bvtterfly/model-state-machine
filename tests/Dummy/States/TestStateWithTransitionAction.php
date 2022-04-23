<?php

namespace Bvtterfly\ModelStateMachine\Tests\Dummy\States;

use Bvtterfly\ModelStateMachine\Attributes\AllowTransitionTo;
use Bvtterfly\ModelStateMachine\Attributes\DefaultState;
use Bvtterfly\ModelStateMachine\Tests\Dummy\Actions\TestAction;

enum TestStateWithTransitionAction: string
{
    #[DefaultState]
    #[AllowTransitionTo(self::B, [TestAction::class])]
    case A = 'a';

    case B = 'b';
}
