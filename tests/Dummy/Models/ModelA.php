<?php

namespace Bvtterfly\ModelStateMachine\Tests\Dummy\Models;

use Bvtterfly\ModelStateMachine\HasStateMachine;
use Bvtterfly\ModelStateMachine\Tests\Dummy\States\TestState;

class ModelA extends StateTransitionModel
{
    use HasStateMachine;

    protected $casts = [
        'state' => TestState::class,
    ];

    public function getStateMachineFields(): array
    {
        return ['state'];
    }
}
