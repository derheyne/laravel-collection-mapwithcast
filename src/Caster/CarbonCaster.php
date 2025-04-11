<?php

namespace dhy\LaravelMapWithCastMacro\Caster;

use Carbon\Carbon;
use Carbon\Month;
use Carbon\WeekDay;
use DateTimeInterface;
use dhy\LaravelMapWithCastMacro\Contract\Caster;
use Illuminate\Support\Carbon as LaravelCarbon;
use InvalidArgumentException;

class CarbonCaster implements Caster
{
    public function qualifies(mixed $type): bool
    {
        if (! is_string($type)) {
            return false;
        }

        return is_a(object_or_class: $type, class: LaravelCarbon::class, allow_string: true)
            || is_a(object_or_class: $type, class: Carbon::class, allow_string: true);
    }

    public function cast(mixed $value, mixed $type): mixed
    {
        if (
            ! $value instanceof DateTimeInterface
            && ! $value instanceof WeekDay
            && ! $value instanceof Month
            && ! is_string($value)
            && ! is_int($value)
            && ! is_float($value)
            && ! is_null($value)
        ) {
            throw new InvalidArgumentException('Value for type ['.$type.'] must be compatible with Carbon::__construct.');
        }

        return new $type($value);
    }
}
