<?php

namespace Bvtterfly\ModelStateMachine\Tests\Dummy\States;

use Bvtterfly\ModelStateMachine\Attributes\Actions;
use Bvtterfly\ModelStateMachine\Attributes\AllowTransitionTo;
use Bvtterfly\ModelStateMachine\Attributes\InitialState;
use Bvtterfly\ModelStateMachine\Tests\Dummy\Actions\TestAction;
use Bvtterfly\ModelStateMachine\Tests\Dummy\Actions\TestActionWithValidation;

enum TestStateWithAction: string
{
    #[InitialState]
    #[AllowTransitionTo(self::B)]
    #[Actions(TestAction::class)]
    case A = 'a';

    #[AllowTransitionTo(self::A)]
    #[Actions(TestActionWithValidation::class)]
    case B = 'b';
}
