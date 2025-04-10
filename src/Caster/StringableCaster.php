<?php

namespace dhy\LaravelMapWithCastMacro\Caster;

use dhy\LaravelMapWithCastMacro\Contract\Caster;
use Illuminate\Support\Stringable;
use InvalidArgumentException;
use Stringable as BaseStringable;

class StringableCaster implements Caster
{
    public function qualifies(mixed $type): bool
    {
        if (! is_string($type)) {
            return false;
        }

        return is_a(object_or_class: $type, class: Stringable::class, allow_string: true);
    }

    public function cast(mixed $value, mixed $type): Stringable
    {
        if ($value !== null && ! is_scalar($value) && ! $value instanceof BaseStringable) {
            throw new InvalidArgumentException('Value for type ['.$type.'] must be stringable.');
        }

        return new $type($value);
    }
}
