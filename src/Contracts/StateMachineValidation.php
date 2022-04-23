<?php

namespace Bvtterfly\ModelStateMachine\Contracts;

use Illuminate\Database\Eloquent\Model;

interface StateMachineValidation
{
    /**
     * Validates the transition,.
     *
     * @param Model $model The object that the transition belongs to it.
     * @param array $additionalData
     */
    public function validate(Model $model, array $additionalData): void;
}
