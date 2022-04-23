<?php

namespace Bvtterfly\ModelStateMachine\Tests\Dummy\Models;

use Illuminate\Database\Eloquent\Model;

class StateTransitionModel extends Model
{
    protected $fillable = ['state'];

    private bool $saved = false;

    public function save(array $options = [])
    {
        if ($this->fireModelEvent('creating') === false) {
            return false;
        }
        $this->saved = true;
    }

    public function getSaved(): bool
    {
        return $this->saved;
    }
}
