<?php

namespace Bvtterfly\ModelStateMachine;

use Bvtterfly\ModelStateMachine\DataTransferObjects\StateMachineConfig;
use Bvtterfly\ModelStateMachine\Exceptions\CouldNotFindStateMachineField;
use Bvtterfly\ModelStateMachine\Exceptions\FieldWithoutCast;
use Illuminate\Database\Eloquent\Model;

trait HasStateMachine
{
    public static function bootHasStateMachine(): void
    {
        self::creating(function (Model $model) {
            /**
             * @var HasStateMachine $model
             */
            $model->setStateDefaults();
        });
    }

    public function getStateMachineFields(): array
    {
        return [];
    }

    private function setStateDefaults(): void
    {
        foreach ($this->getStateMachineFields() as $field) {
            if ($this->{$field} !== null) {
                continue;
            }

            $stateMachineConfig = $this->getStateMachineConfig($field);

            $defaultValue = $stateMachineConfig->default;
            if ($defaultValue === null) {
                continue;
            }

            $this->{$field} = $defaultValue;
        }
    }

    public function getStateMachineConfig(string $field): StateMachineConfig
    {
        $this->isStateMachineField($field);

        return ConfigLoader::load($this->getCasts()[$field]);
    }

    public function getStateMachine(string $field): StateMachine
    {
        $this->isStateMachineField($field);

        return new StateMachine($this, $this->getCasts()[$field], $field);
    }

    private function isStateMachineField(string $field): bool
    {
        if (! in_array($field, $this->getStateMachineFields())) {
            throw CouldNotFindStateMachineField::make($field);
        }
        if (! $this->hasCast($field)) {
            throw FieldWithoutCast::make($field);
        }

        return true;
    }
}
