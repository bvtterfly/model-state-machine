<?php

namespace Bvtterfly\ModelStateMachine\Tests\Dummy\Actions;

use Bvtterfly\ModelStateMachine\Contracts\StateMachineAction;
use Bvtterfly\ModelStateMachine\Contracts\StateMachineValidation;
use Illuminate\Database\Eloquent\Model;

class TestActionWithValidation implements StateMachineAction, StateMachineValidation
{
    public function handle(Model $model, array $additionalData): void
    {
    }

    public function validate(Model $model, array $additionalData): void
    {
    }
}
