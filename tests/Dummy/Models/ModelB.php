<?php

namespace Bvtterfly\ModelStateMachine\Tests\Dummy\Models;

use Bvtterfly\ModelStateMachine\HasStateMachine;

class ModelB extends StateTransitionModel
{
    use HasStateMachine;

    protected $casts = [];

    public function getStateMachineFields(): array
    {
        return ['state'];
    }
}
