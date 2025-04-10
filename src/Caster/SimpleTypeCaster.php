<?php

namespace dhy\LaravelMapWithCastMacro\Caster;

use dhy\LaravelMapWithCastMacro\Contract\Caster;
use InvalidArgumentException;

class SimpleTypeCaster implements Caster
{
    protected const array TYPE_CAST_MAP = [
        'null' => ['int', 'float', 'string', 'bool', 'array', 'object'],
        'int' => ['float', 'string', 'bool', 'array', 'object'],
        'float' => ['int', 'string', 'bool', 'array', 'object'],
        'string' => ['int', 'float', 'bool', 'array', 'object'],
        'bool' => ['int', 'float', 'string', 'array', 'object'],
        'array' => ['object'],
        'object' => ['array'],
    ];

    protected const array TYPE_NAME_MAP = [
        'string' => 'string',
        'integer' => 'int',
        'double' => 'float',
        'boolean' => 'bool',
        'array' => 'array',
        'object' => 'object',
        'NULL' => 'null',
    ];

    public function qualifies(mixed $type): bool
    {
        return isset(self::TYPE_CAST_MAP[$type]);
    }

    public function cast(mixed $value, mixed $type): mixed
    {
        $fromType = self::TYPE_NAME_MAP[gettype($value)] ?? null;

        if ($fromType === $type) {
            return $value;
        }

        if (! $this->canBeCasted(from: $fromType, to: $type)) {
            throw new InvalidArgumentException('Value cannot be cast to type ['.$type.'].');
        }

        settype(var: $value, type: $type);

        return $value;
    }

    protected function canBeCasted(string $from, string $to): bool
    {
        if (! isset(self::TYPE_CAST_MAP[$from])) {
            return false;
        }

        return in_array(needle: strtolower($to), haystack: self::TYPE_CAST_MAP[$from], strict: true);
    }
}
