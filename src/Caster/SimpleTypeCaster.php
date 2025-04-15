<?php

namespace dhy\LaravelMapWithCastMacro\Caster;

use dhy\LaravelMapWithCastMacro\Contract\Caster;
use Error;
use ErrorException;
use InvalidArgumentException;

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
            return match ($type) {
                'string' => (string) $value,
                'int' => (int) $value,
                'float' => (float) $value,
                'bool' => (bool) $value,
                'array' => (array) $value,
                'object' => (object) $value,
            };
        } catch (ErrorException|Error $exception) {
            throw new InvalidArgumentException(
                message: 'Value cannot be cast to type ['.$type.'].',
                previous: $exception,
            );
        }
    }
}
