<?php

namespace Bvtterfly\ModelStateMachine\DataTransferObjects;

use Illuminate\Support\Collection;

class StateMachineConfig
{
    /**
     * @param  string|null  $initial
     * @param  Collection<string, StateConfig>  $states
     */
    public function __construct(
        public readonly ?string $initial,
        public readonly Collection $states,
    ) {
    }

    public function getStateTransitions(string $state): Collection
    {
        return $this->states->get($state)->transitions;
    }

    public function getStateTransitionConfig(string $from, string $to): StateTransitionConfig
    {
        $stateTransitions = $this->getStateTransitions($from);

        return $stateTransitions->firstWhere('to', $to);
    }

    public function getTransitionActions(string $from, string $to): Collection
    {
        $stateTransitionConfig = $this->getStateTransitionConfig($from, $to);

        return $stateTransitionConfig->getActions();
    }

    public function getStateActions(string $state): Collection
    {
        return $this->states->get($state)->actions;
    }
}
