<?php

namespace Bvtterfly\ModelStateMachine;

use Bvtterfly\ModelStateMachine\Contracts\StateMachineAction;
use Bvtterfly\ModelStateMachine\Contracts\StateMachineValidation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class TransitionManager
{
    /**
     * TransitionManager constructor.
     *
     * @param  Model  $model
     * @param  Collection<int, class-string<StateMachineAction>>  $actions
     * @param  array  $additionalData
     */
    public function __construct(
        private readonly Model $model,
        private readonly Collection $actions,
        private readonly array $additionalData
    ) {
    }

    private function validate(): void
    {
        $validations = $this->actions->filter(fn (string $action) => is_subclass_of($action, StateMachineValidation::class));
        $this->performValidations($validations);
    }

    private function performValidations(Collection $validations): void
    {
        foreach ($validations as $validation) {
            /** @var StateMachineValidation $handler */
            $handler = app()->make($validation);
            $handler->validate($this->model, $this->additionalData);
        }
    }

    private function performActions(): void
    {
        foreach ($this->actions as $action) {
            /** @var StateMachineAction $handler */
            $handler = app()->make($action);
            $handler->handle($this->model, $this->additionalData);
        }
    }

    /**
     * Validates the transition and performs the transition and destination actions.
     */
    public function transit(): void
    {
        $this->validate();
        $this->performActions();
    }
}
