<?php

namespace Bvtterfly\ModelStateMachine;

use BackedEnum;
use Bvtterfly\ModelStateMachine\Contracts\StateTransition;
use Illuminate\Database\Eloquent\Model;

class DefaultStateTransition implements StateTransition
{
    public function commitTransition(BackedEnum|string $newState, Model $model, string $field, array $additionalData): void
    {
        $model->{$field} = $newState;
        $model->save();
    }
}
