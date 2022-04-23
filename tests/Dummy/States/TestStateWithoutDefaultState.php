<?php

namespace Bvtterfly\ModelStateMachine\Tests\Dummy\States;

use Bvtterfly\ModelStateMachine\Attributes\AllowTransitionTo;

enum TestStateWithoutDefaultState: string
{
    #[AllowTransitionTo(self::B)]
    case A = 'a';

    #[AllowTransitionTo(self::A)]
    case B = 'b';
}
