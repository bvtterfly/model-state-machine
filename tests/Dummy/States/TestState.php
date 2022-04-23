<?php

namespace Bvtterfly\ModelStateMachine\Tests\Dummy\States;

use Bvtterfly\ModelStateMachine\Attributes\AllowTransitionTo;
use Bvtterfly\ModelStateMachine\Attributes\DefaultState;

enum TestState: string
{
    #[DefaultState]
    #[AllowTransitionTo(self::B)]
    case A = 'a';

    #[AllowTransitionTo(self::A)]
    case B = 'b';
}
