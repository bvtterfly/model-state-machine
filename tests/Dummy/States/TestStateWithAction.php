<?php

namespace Bvtterfly\ModelStateMachine\Tests\Dummy\States;

use Bvtterfly\ModelStateMachine\Attributes\Actions;
use Bvtterfly\ModelStateMachine\Attributes\AllowTransitionTo;
use Bvtterfly\ModelStateMachine\Attributes\DefaultState;
use Bvtterfly\ModelStateMachine\Tests\Dummy\Actions\TestAction;
use Bvtterfly\ModelStateMachine\Tests\Dummy\Actions\TestActionWithValidation;

enum TestStateWithAction: string
{
    #[DefaultState]
    #[AllowTransitionTo(self::B)]
    #[Actions(TestAction::class)]
    case A = 'a';

    #[AllowTransitionTo(self::A)]
    #[Actions(TestActionWithValidation::class)]
    case B = 'b';
}
