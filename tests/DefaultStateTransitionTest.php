<?php

use Bvtterfly\ModelStateMachine\DefaultStateTransition;
use Bvtterfly\ModelStateMachine\Tests\Dummy\Models\StateTransitionModel;

it('should save new value', function () {
    $model = new StateTransitionModel([
        'state' => 'test-state',
    ]);

    expect($model)->state->toEqual('test-state');

    /** @var DefaultStateTransition $stateTransition */
    $stateTransition = app(DefaultStateTransition::class);

    $stateTransition->commitTransition('new-test-state', $model, 'state', []);

    expect($model)->getSaved()->toBeTrue();
    expect($model)->state->toEqual('new-test-state');
});
