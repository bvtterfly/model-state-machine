<?php

namespace Bvtterfly\ModelStateMachine\Contracts;

use Illuminate\Database\Eloquent\Model;

interface StateMachineAction
{
    /**
     * Performs action,.
     *
     * @param Model $model The object that the transition belongs to it.
     * @param array $additionalData
     */
    public function handle(Model $model, array $additionalData): void;
}
