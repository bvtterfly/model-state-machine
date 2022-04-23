<?php

namespace Bvtterfly\ModelStateMachine\Contracts;

use BackedEnum;
use Illuminate\Database\Eloquent\Model;

interface StateTransition
{
    /**
     * Commit transition,.
     *
     * @param  string|BackedEnum  $newState
     * @param  Model  $model  The object that the transition belongs to it.
     * @param  string  $field
     * @param  array  $additionalData
     */
    public function commitTransition(string|BackedEnum $newState, Model $model, string $field, array $additionalData): void;
}
