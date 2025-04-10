<?php

namespace dhy\LaravelMapWithCastMacro\Caster;

use ArrayAccess;
use dhy\LaravelMapWithCastMacro\Contract\Caster;
use Illuminate\Support\Fluent;
use InvalidArgumentException;

class FluentCaster implements Caster
{
    public function qualifies(mixed $type): bool
    {
        if (! is_string($type)) {
            return false;
        }

        return is_a(object_or_class: $type, class: Fluent::class, allow_string: true);
    }

    public function cast(mixed $value, mixed $type): Fluent
    {
        if (! is_iterable($value) && ! $value instanceof ArrayAccess) {
            throw new InvalidArgumentException('Value for type ['.$type.'] must be iterable.');
        }

        return new $type($value);
    }
}
