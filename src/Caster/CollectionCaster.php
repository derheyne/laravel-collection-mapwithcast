<?php

namespace dhy\LaravelMapWithCastMacro\Caster;

use dhy\LaravelMapWithCastMacro\Contract\Caster;
use Illuminate\Support\Collection;

class CollectionCaster implements Caster
{
    public function qualifies(mixed $type): bool
    {
        if (! is_string($type)) {
            return false;
        }

        return is_a(object_or_class: $type, class: Collection::class, allow_string: true);
    }

    /** @param  Collection  $type */
    public function cast(mixed $value, mixed $type): Collection
    {
        return new $type($value);
    }
}
