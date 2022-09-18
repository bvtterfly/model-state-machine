<?php

namespace Bvtterfly\ModelStateMachine\Tests\Dummy\States;

use Bvtterfly\ModelStateMachine\Attributes\AllowTransitionTo;
use Bvtterfly\ModelStateMachine\Attributes\InitialState;

enum TestState: string
{
    #[InitialState]
    #[AllowTransitionTo(self::B)]
    case A = 'a';

    #[AllowTransitionTo(self::A)]
    case B = 'b';
}
