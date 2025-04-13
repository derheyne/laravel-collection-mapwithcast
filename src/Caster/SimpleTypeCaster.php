<?php

namespace dhy\LaravelMapWithCastMacro\Caster;

use dhy\LaravelMapWithCastMacro\Contract\Caster;
use InvalidArgumentException;
use Throwable;

class SimpleTypeCaster implements Caster
{
    protected const array AVAILABLE_TYPES = ['null', 'int', 'float', 'string', 'bool', 'array', 'object'];

    public function qualifies(mixed $type): bool
    {
        return in_array($type, self::AVAILABLE_TYPES, true);
    }

    public function cast(mixed $value, mixed $type): mixed
    {
        try {
            settype(var: $value, type: $type);
        } catch (Throwable) {
            throw new InvalidArgumentException('Value cannot be cast to type ['.$type.'].');
        }

        return $value;
    }
}
