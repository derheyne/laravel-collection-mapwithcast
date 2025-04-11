<?php

namespace dhy\LaravelMapWithCastMacro\Caster;

use dhy\LaravelMapWithCastMacro\Contract\Caster;
use Illuminate\Support\Optional;

class OptionalCaster implements Caster
{
    public function qualifies(mixed $type): bool
    {
        if (! is_string($type)) {
            return false;
        }

        return is_a(object_or_class: $type, class: Optional::class, allow_string: true);
    }

    /** @param  Optional  $type */
    public function cast(mixed $value, mixed $type): mixed
    {
        return new $type($value);
    }
}
