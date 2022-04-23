<?php

namespace Bvtterfly\ModelStateMachine\DataTransferObjects;

use Bvtterfly\ModelStateMachine\Validator;
use Illuminate\Support\Collection;

class StateConfig
{
    public function __construct(
        public readonly Collection $transitions,
        public readonly Collection $actions,
    ) {
        foreach ($actions as $action) {
            Validator::validateAction($action);
        }
    }
}
