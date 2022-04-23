<?php

use Bvtterfly\ModelStateMachine\ConfigLoader;
use Bvtterfly\ModelStateMachine\Contracts\StateMachineAction;
use Bvtterfly\ModelStateMachine\Contracts\StateTransition;
use Bvtterfly\ModelStateMachine\DataTransferObjects\StateConfig;
use Bvtterfly\ModelStateMachine\DataTransferObjects\StateTransitionConfig;
use Bvtterfly\ModelStateMachine\Exceptions\InvalidStateMachineConfig;
use Bvtterfly\ModelStateMachine\Tests\Dummy\Actions\TestAction;
use Bvtterfly\ModelStateMachine\Tests\Dummy\Actions\TestActionWithValidation;
use Bvtterfly\ModelStateMachine\Tests\Dummy\Actions\TestInvalidAction;
use Bvtterfly\ModelStateMachine\Tests\Dummy\States\SimpleEnum;
use Bvtterfly\ModelStateMachine\Tests\Dummy\States\TestState;
use Bvtterfly\ModelStateMachine\Tests\Dummy\States\TestStateWithAction;
use Bvtterfly\ModelStateMachine\Tests\Dummy\States\TestStateWithInvalidAction;
use Bvtterfly\ModelStateMachine\Tests\Dummy\States\TestStateWithInvalidStateTransition;
use Bvtterfly\ModelStateMachine\Tests\Dummy\States\TestStateWithMultipleActions;
use Bvtterfly\ModelStateMachine\Tests\Dummy\States\TestStateWithStateTransition;
use Bvtterfly\ModelStateMachine\Tests\Dummy\States\TestStateWithTransitionAction;
use Bvtterfly\ModelStateMachine\Tests\Dummy\StateTransitions\CustomStateTransition;
use Bvtterfly\ModelStateMachine\Tests\Dummy\StateTransitions\InvalidStateTransition;

it('can load configs from a simple enum', function () {
    $config = ConfigLoader::load(TestState::class);
    expect($config)->default->toEqual('a');
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

it('can load configs from enum with transition action', function () {
    $config = ConfigLoader::load(TestStateWithTransitionAction::class);
    expect($config)->default->toEqual('a');
    $states = collect([
        'a' => new StateConfig(
            transitions: collect([
                new StateTransitionConfig('b', [TestAction::class]),
            ]),
            actions: collect(),
        ),
        'b' => new StateConfig(
            transitions: collect(),
            actions: collect(),
        ),
    ]);
    expect($config)->states->toEqual($states);
});

it('can load configs from enum with state transition', function () {
    $config = ConfigLoader::load(TestStateWithStateTransition::class);
    expect($config)->default->toEqual('a');
    $states = collect([
        'a' => new StateConfig(
            transitions: collect([
                new StateTransitionConfig('b', transition: CustomStateTransition::class),
            ]),
            actions: collect(),
        ),
        'b' => new StateConfig(
            transitions: collect(),
            actions: collect(),
        ),
    ]);
    expect($config)->states->toEqual($states);
});

it('cant load configs from enum with invalid state transition', function () {
    ConfigLoader::load(TestStateWithInvalidStateTransition::class);
})->throws(InvalidStateMachineConfig::class, InvalidStateTransition::class.' should implements '.StateTransition::class.'.');

it('can load configs from enum with action', function () {
    $config = ConfigLoader::load(TestStateWithAction::class);
    expect($config)->default->toEqual('a');
    $states = collect([
        'a' => new StateConfig(
            transitions: collect([
                new StateTransitionConfig('b'),
            ]),
            actions: collect([TestAction::class]),
        ),
        'b' => new StateConfig(
            transitions: collect([
                new StateTransitionConfig('a'),
            ]),
            actions: collect([TestActionWithValidation::class]),
        ),
    ]);
    expect($config)->states->toEqual($states);
});

it('can load configs from enum with multiple action', function () {
    $config = ConfigLoader::load(TestStateWithMultipleActions::class);
    expect($config)->default->toBeNull();
    $states = collect([
        'a' => new StateConfig(
            transitions: collect(),
            actions: collect([TestAction::class, TestActionWithValidation::class]),
        ),
    ]);
    expect($config)->states->toEqual($states);
});

it('cant load configs from enum with invalid action', function () {
    ConfigLoader::load(TestStateWithInvalidAction::class);
})->throws(InvalidStateMachineConfig::class, TestInvalidAction::class.' should implements '.StateMachineAction::class.'.');

it('cant load configs from class', function () {
    ConfigLoader::load(TestAction::class);
})->throws(InvalidStateMachineConfig::class, TestAction::class.' should be an enum.');

it('cant load configs from not backed enum', function () {
    ConfigLoader::load(SimpleEnum::class);
})->throws(InvalidStateMachineConfig::class, SimpleEnum::class.' should be a backed enum.');
