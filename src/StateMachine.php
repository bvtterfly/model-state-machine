<?php

namespace Bvtterfly\ModelStateMachine;

use BackedEnum;
use Bvtterfly\ModelStateMachine\DataTransferObjects\StateMachineConfig;
use Bvtterfly\ModelStateMachine\DataTransferObjects\StateTransitionConfig;
use Bvtterfly\ModelStateMachine\Exceptions\InvalidTransition;
use Bvtterfly\ModelStateMachine\Exceptions\UnknownState;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class StateMachine
{
    private readonly StateMachineConfig $config;

    public function __construct(private readonly Model $model, private readonly string $enumClass, private readonly string $field)
    {
        $this->config = ConfigLoader::load($this->enumClass);
    }

    /**
     * @return Collection
     */
    public function getAllStates(): Collection
    {
        return $this->config->states->keys();
    }

    /**
     * @param BackedEnum|string|null $state
     * @return Collection
     * @throws \Exception
     */
    public function getStateTransitions(BackedEnum|string|null $state = null): Collection
    {
        $state ??= $this->currentState();

        if ($state instanceof BackedEnum) {
            $this->checkValidEnum($state);
            $state = $state->value;
        }

        $states = $this->config->states;
        if (! $states->has($state)) {
            throw UnknownState::make();
        }

        return $states
            ->get($state)
            ->transitions
            ->pluck('to');
    }

    /**
     * @param  string  $sourceState
     * @param  string  $destinationState
     *
     * @return bool
     * @throws Exception
     */
    protected function isValidTransition(string $sourceState, string $destinationState): bool
    {
        return $this->getStateTransitions($sourceState)->contains($destinationState);
    }

    /**
     * @param string $sourceState
     * @param string $destinationState
     * @throws \Exception
     */
    private function validateTransitionExistence(string $sourceState, string $destinationState): void
    {
        $states = $this->config->states;
        if (! $states->has($sourceState) || ! $states->has($destinationState)) {
            throw UnknownState::make();
        }

        if (! $this->isValidTransition($sourceState, $destinationState)) {
            throw InvalidTransition::make($sourceState, $destinationState);
        }
    }

    private function currentState(): ?string
    {
        $state = $this->model->{$this->field};
        if (! $state) {
            $state = $this->config->default;
            if (! $state) {
                throw UnknownState::make();
            }

            return $state;
        }

        return $this->model->{$this->field}->value;
    }

    public function transitionTo(BackedEnum|string $newState, array $additionalData = [])
    {
        $newStateVal = $newState;
        if (! is_string($newState)) {
            $this->checkValidEnum($newState);
            $newStateVal = $newState->value;
        }
        $currentState = $this->currentState();
        $this->validateTransitionExistence($currentState, $newStateVal);
        /** @var Collection $sourceStateTransitions */
        $sourceStateTransitions = $this->config->states->get($currentState)->transitions;
        /** @var StateTransitionConfig $stateTransitionConfig */
        $stateTransitionConfig = $sourceStateTransitions->firstWhere('to', $newStateVal);
        $sourceStateActions = $stateTransitionConfig->actions;
        $destinationStateActions = $this->config->states->get($newStateVal)->actions;
        $actions = collect($sourceStateActions)->concat($destinationStateActions);
        $stateMachineTransition = new TransitionManager($this->model, $actions, $additionalData);
        $stateMachineTransition->transit();
        $stateTransitionConfig->getStateTransition()->commitTransition($newState, $this->model, $this->field, $additionalData);
    }

    private function checkValidEnum(BackedEnum $state)
    {
        throw_unless($state instanceof $this->enumClass, UnknownState::make());
    }
}
