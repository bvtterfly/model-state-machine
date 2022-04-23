<?php

use Bvtterfly\ModelStateMachine\Exceptions\InvalidTransition;
use Bvtterfly\ModelStateMachine\Exceptions\UnknownState;
use Bvtterfly\ModelStateMachine\StateMachine;
use Bvtterfly\ModelStateMachine\Tests\Dummy\Models\ModelA;
use Bvtterfly\ModelStateMachine\Tests\Dummy\Models\ModelC;
use Bvtterfly\ModelStateMachine\Tests\Dummy\States\TestState;
use Bvtterfly\ModelStateMachine\Tests\Dummy\States\TestStateWithAction;
use Bvtterfly\ModelStateMachine\Tests\Dummy\States\TestStateWithoutDefaultState;
use Bvtterfly\ModelStateMachine\Tests\Dummy\States\TestStateWithStateTransition;

it('can get all states', function () {
    $model = new ModelA();
    $stateMachine = new StateMachine($model, TestState::class, 'state');
    expect($stateMachine)->getAllStates()->toEqual(collect(['a', 'b']));
});

it('can get transitions', function () {
    $model = new ModelA(['state' => 'a']);
    $stateMachine = new StateMachine($model, TestState::class, 'state');
    expect($stateMachine)->getStateTransitions()->toEqual(collect(['b']));
    expect($stateMachine)->getStateTransitions('b')->toEqual(collect(['a']));
});

it('can get transitions from enum state', function () {
    $model = new ModelA(['state' => 'a']);
    $stateMachine = new StateMachine($model, TestState::class, 'state');
    expect($stateMachine)->getStateTransitions(TestState::B)->toEqual(collect(['a']));
});

it('cant get transitions if has a null state without default state', function () {
    $model = new ModelA();
    $stateMachine = new StateMachine($model, TestStateWithoutDefaultState::class, 'state');
    expect($stateMachine)->getStateTransitions();
})->throws(UnknownState::class);

it('cant get transitions if has a unknown state', function () {
    $model = new ModelA();
    $stateMachine = new StateMachine($model, TestState::class, 'state');
    expect($stateMachine)->getStateTransitions('f');
})->throws(UnknownState::class);

it('cant get transitions if has a state from another enum', function () {
    $model = new ModelA();
    $stateMachine = new StateMachine($model, TestState::class, 'state');
    expect($stateMachine)->getStateTransitions(TestStateWithAction::A);
})->throws(UnknownState::class);

it('cant transit to another state if has a unknown state', function () {
    $model = new ModelA();
    $stateMachine = new StateMachine($model, TestStateWithoutDefaultState::class, 'state');
    expect($stateMachine)->transitionTo(TestStateWithoutDefaultState::B);
})->throws(UnknownState::class);

it('cant transit to unknown state', function () {
    $model = new ModelA();
    $stateMachine = new StateMachine($model, TestState::class, 'state');
    expect($stateMachine)->transitionTo('c');
})->throws(UnknownState::class);

it('cant transit to invalid state', function () {
    $model = new ModelC(['state' => 'b']);
    $stateMachine = new StateMachine($model, TestStateWithStateTransition::class, 'state');
    expect($stateMachine)->transitionTo('a');
})->throws(InvalidTransition::class, 'The "b" state is not allowed to transit to "a" state.');

it('can transit to valid state', function () {
    $model = new ModelA();
    $stateMachine = $model->getStateMachine('state');
    $stateMachine->transitionTo('b');
    expect($model)->getSaved()->toBeTrue();
    expect($model)->state->toEqual(TestState::B);
});
