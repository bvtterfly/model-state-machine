<?php

namespace Bvtterfly\ModelStateMachine\Tests\Dummy\States;

use Bvtterfly\ModelStateMachine\Attributes\Actions;
use Bvtterfly\ModelStateMachine\Tests\Dummy\Actions\TestAction;
use Bvtterfly\ModelStateMachine\Tests\Dummy\Actions\TestActionWithValidation;

enum TestStateWithMultipleActions: string
{
    #[Actions(TestAction::class, TestActionWithValidation::class)]
    case A = 'a';
}
