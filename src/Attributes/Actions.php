<?php

namespace Bvtterfly\ModelStateMachine\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class Actions
{
    public readonly array $actions;

    public function __construct(
        string ...$actions
    ) {
        $this->actions = $actions;
    }
}
