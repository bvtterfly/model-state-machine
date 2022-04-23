<?php

use Bvtterfly\ModelStateMachine\Contracts\StateMachineAction;
use Bvtterfly\ModelStateMachine\Contracts\StateTransition;
use Bvtterfly\ModelStateMachine\Exceptions\InvalidStateMachineConfig;
use Bvtterfly\ModelStateMachine\Tests\Dummy\Actions\TestAction;
use Bvtterfly\ModelStateMachine\Tests\Dummy\Actions\TestInvalidAction;
use Bvtterfly\ModelStateMachine\Tests\Dummy\StateTransitions\CustomStateTransition;
use Bvtterfly\ModelStateMachine\Tests\Dummy\StateTransitions\InvalidStateTransition;
use Bvtterfly\ModelStateMachine\Validator;

test('action should implements action interface', function () {
    Validator::validateAction(TestAction::class);
    expect(true)->toBeTrue();
});

it('throws exception if action doesnt implements action interface', function () {
    Validator::validateAction(TestInvalidAction::class);
})->throws(InvalidStateMachineConfig::class, TestInvalidAction::class.' should implements '.StateMachineAction::class.'.');

test('transition should implements transition interface', function () {
    Validator::validateTransition(CustomStateTransition::class);
    expect(true)->toBeTrue();
});

it('throws exception if transition doesnt implements transition interface', function () {
    Validator::validateTransition(InvalidStateTransition::class);
})->throws(InvalidStateMachineConfig::class, InvalidStateTransition::class.' should implements '.StateTransition::class.'.');
