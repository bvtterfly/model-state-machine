<?php

namespace Bvtterfly\ModelStateMachine\Tests\Dummy\Actions;

use Bvtterfly\ModelStateMachine\Contracts\StateMachineAction;
use Illuminate\Database\Eloquent\Model;

class TestAction implements StateMachineAction
{
    public function handle(Model $model, array $additionalData): void
    {
    }
}
