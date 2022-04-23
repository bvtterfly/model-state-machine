<?php

namespace Bvtterfly\ModelStateMachine\Tests\Dummy\StateTransitions;

use BackedEnum;
use Bvtterfly\ModelStateMachine\Contracts\StateTransition;
use Illuminate\Database\Eloquent\Model;

class CustomStateTransition implements StateTransition
{
    public function commitTransition(
        BackedEnum|string $newState,
        Model $model,
        string $field,
        array $additionalData
    ): void {
    }
}
