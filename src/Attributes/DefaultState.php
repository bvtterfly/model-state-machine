<?php

namespace Bvtterfly\ModelStateMachine\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class DefaultState
{
    public function __construct(
    ) {
    }
}
