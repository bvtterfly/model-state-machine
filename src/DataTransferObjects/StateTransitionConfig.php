<?php

namespace Bvtterfly\ModelStateMachine\DataTransferObjects;

use Bvtterfly\ModelStateMachine\Contracts\StateTransition;
use Bvtterfly\ModelStateMachine\DefaultStateTransition;
use Bvtterfly\ModelStateMachine\Validator;

class StateTransitionConfig
{
    public function __construct(
        public readonly string $to,
        public readonly array $actions = [],
        public readonly string $transition = DefaultStateTransition::class,
    ) {
        foreach ($actions as $action) {
            Validator::validateAction($action);
        }
        Validator::validateTransition($transition);
    }

    public function getStateTransition(): StateTransition
    {
        return app()->make($this->transition);
    }
}
