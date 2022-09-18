<?php

namespace Bvtterfly\ModelStateMachine\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class InitialState
{
    public function __construct(
    ) {
    }
}
