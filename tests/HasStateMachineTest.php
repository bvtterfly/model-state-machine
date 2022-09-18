<?php

use Bvtterfly\ModelStateMachine\DataTransferObjects\StateConfig;
use Bvtterfly\ModelStateMachine\DataTransferObjects\StateTransitionConfig;
use Bvtterfly\ModelStateMachine\Exceptions\CouldNotFindStateMachineField;
use Bvtterfly\ModelStateMachine\Exceptions\FieldWithoutCast;
use Bvtterfly\ModelStateMachine\StateMachine;
use Bvtterfly\ModelStateMachine\Tests\Dummy\Models\ModelA;
use Bvtterfly\ModelStateMachine\Tests\Dummy\Models\ModelB;
use Bvtterfly\ModelStateMachine\Tests\Dummy\States\TestState;

it('can get state config', function () {
    $model = new ModelA();
    $config = $model->getStateMachineConfig('state');
    expect($config)->initial->toEqual('a');
    $states = collect([
        'a' => new StateConfig(
            transitions: collect([
                new StateTransitionConfig('b'),
            ]),
            actions: collect(),
        ),
        'b' => new StateConfig(
            transitions: collect([
                new StateTransitionConfig('a'),
            ]),
            actions: collect(),
        ),
    ]);
    expect($config)->states->toEqual($states);
});

it('can get state machine', function () {
    $model = new ModelA();
    $stateMachine = $model->getStateMachine('state');
    expect($stateMachine)->toEqual(new StateMachine($model, TestState::class, 'state'));
});



it('can set default state', function () {
    $model = new ModelA();
    $model->save();
    expect($model)->getSaved()->toBeTrue();
    expect($model)->state->toEqual(TestState::A);
});

it('cant set default state if state has value', function () {
    $model = new ModelA(['state' => TestState::B]);
    $model->save();
    expect($model)->getSaved()->toBeTrue();
    expect($model)->state->toEqual(TestState::B);
});

it('cant get state config for attribute does not define as a state machine field', function () {
    $model = new ModelA();
    $model->getStateMachineConfig('test');
})->throws(CouldNotFindStateMachineField::class, "\"test\" isn't a state machine field.");

it('cant get state config for attribute without cast', function () {
    $model = new ModelB();
    $model->getStateMachineConfig('state');
})->throws(FieldWithoutCast::class, "\"state\" attribute doesn't have a cast.");
