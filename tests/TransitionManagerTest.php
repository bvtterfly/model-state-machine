<?php

use Bvtterfly\ModelStateMachine\Tests\Dummy\Actions\TestAction;
use Bvtterfly\ModelStateMachine\Tests\Dummy\Actions\TestActionWithValidation;
use Bvtterfly\ModelStateMachine\Tests\Dummy\Models\StateTransitionModel;
use Bvtterfly\ModelStateMachine\TransitionManager;

it('should call actions', function () {
    $model = new StateTransitionModel();
    $testActionMock = mock(TestAction::class);
    $testActionMock->shouldReceive('handle')->once();
    app()->instance(TestAction::class, $testActionMock);
    $manager = new TransitionManager($model, collect([TestAction::class]), []);
    $manager->transit();
});

it('should call actions and validations', function () {
    $model = new StateTransitionModel();
    $testActionMock = mock(TestAction::class);
    $testActionWithValidationMock = mock(TestActionWithValidation::class);
    $testActionMock->shouldReceive('handle')->once();
    $testActionWithValidationMock->shouldReceive('handle')->once();
    $testActionWithValidationMock->shouldReceive('validate')->once();
    app()->instance(TestAction::class, $testActionMock);
    app()->instance(TestActionWithValidation::class, $testActionWithValidationMock);
    $manager = new TransitionManager($model, collect([TestAction::class, TestActionWithValidation::class]), []);
    $manager->transit();
});
