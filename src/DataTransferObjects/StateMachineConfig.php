<?php

namespace Bvtterfly\ModelStateMachine\DataTransferObjects;

use Illuminate\Support\Collection;

class StateMachineConfig
{
    public function __construct(
        public readonly ?string $default,
        public readonly Collection $states,
    ) {
    }
}
