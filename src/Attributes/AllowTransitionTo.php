<?php

namespace Bvtterfly\ModelStateMachine\Attributes;

use Attribute;
use BackedEnum;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT | Attribute::IS_REPEATABLE)]
class AllowTransitionTo
{
    public function __construct(
        public readonly BackedEnum $to,
        public readonly array $actions = [],
        public readonly ?string $transition = null
    ) {
    }
}
