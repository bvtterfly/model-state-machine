<?php

namespace Bvtterfly\ModelStateMachine;

use Bvtterfly\ModelStateMachine\Attributes\Actions;
use Bvtterfly\ModelStateMachine\Attributes\AllowTransitionTo;
use Bvtterfly\ModelStateMachine\Attributes\InitialState;
use Bvtterfly\ModelStateMachine\DataTransferObjects\StateConfig;
use Bvtterfly\ModelStateMachine\DataTransferObjects\StateMachineConfig;
use Bvtterfly\ModelStateMachine\DataTransferObjects\StateTransitionConfig;
use Bvtterfly\ModelStateMachine\Exceptions\InvalidStateMachineConfig;
use ReflectionEnum;

class ConfigLoader
{
    public static array $cacheConfig = [];

    public static function load(string $enumClass): StateMachineConfig
    {
        if (! enum_exists($enumClass)) {
            throw InvalidStateMachineConfig::enum($enumClass);
        }

        if (! isset(self::$cacheConfig[$enumClass])) {
            self::$cacheConfig[$enumClass] = self::resolve($enumClass);
        }

        return self::$cacheConfig[$enumClass];
    }

    private static function resolve($enumClass): StateMachineConfig
    {
        $initial = null;
        $states = collect();
        $reflectionEnum = new ReflectionEnum($enumClass);

        if (! $reflectionEnum->isBacked()) {
            throw InvalidStateMachineConfig::backedEnum($enumClass);
        }

        foreach ($reflectionEnum->getCases() as $case) {
            $initialStateAttributes = $case->getAttributes(InitialState::class);
            if (count($initialStateAttributes) && ! $initial) {
                $initial = $case->getValue()->value;
            }
            $transitionsAttribute = $case->getAttributes(AllowTransitionTo::class);
            $transitions = collect();
            foreach ($transitionsAttribute as $attribute) {
                /** @var AllowTransitionTo $instance */
                $instance = $attribute->newInstance();
                $transitions->push(new StateTransitionConfig(to: $instance->to->value, actions: $instance->actions, transition: $instance->transition ?? DefaultStateTransition::class));
            }
            $actionsAttributes = $case->getAttributes(Actions::class);
            $actions = collect();
            foreach ($actionsAttributes as $attribute) {
                /** @var Actions $instance */
                $instance = $attribute->newInstance();
                $actions = $actions->concat($instance->actions);
            }
            $caseConfig = new StateConfig(
                transitions: $transitions,
                actions: $actions,
            );
            $states->put($case->getValue()->value, $caseConfig);
        }

        return new StateMachineConfig(initial: $initial, states: $states);
    }
}
