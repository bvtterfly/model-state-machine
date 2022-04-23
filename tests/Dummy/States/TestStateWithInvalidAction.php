<?php

namespace Bvtterfly\ModelStateMachine\Tests\Dummy\States;

use Bvtterfly\ModelStateMachine\Attributes\Actions;
use Bvtterfly\ModelStateMachine\Tests\Dummy\Actions\TestInvalidAction;

enum TestStateWithInvalidAction: string
{
    #[Actions(TestInvalidAction::class)]
    case A = 'a';
}
