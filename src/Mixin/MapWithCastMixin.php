<?php

namespace dhy\LaravelMapWithCastMacro\Mixin;

use dhy\LaravelMapWithCastMacro\Contract\Caster;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use ReflectionFunction;
use ReflectionIntersectionType;
use ReflectionUnionType;

/** @mixin Collection */
class MapWithCastMixin
{
    public function mapWithCast(): callable
    {
        return function (callable $callback, ?callable $caster = null) {
            $expectedType = Arr::first((new ReflectionFunction($callback))->getParameters())->getType();

            if (! $caster) {
                if (! $expectedType) {
                    return new static(Arr::map($this->items, $callback));
                }

                if ($expectedType instanceof ReflectionIntersectionType || $expectedType instanceof ReflectionUnionType) {
                    throw new InvalidArgumentException('Cannot resolve value type from union or intersection type.');
                }

                /** @var ?Caster $caster */
                $caster = collect(config('mapwithcast.casters'))
                    ->map(fn (string $class) => app()->make($class))
                    ->first(fn (Caster $caster) => $caster->qualifies($expectedType->getName()));
            }

            if (! $caster) {
                throw new InvalidArgumentException('No caster configured for type ['.$expectedType->getName().']');
            }

            if ($caster instanceof Caster) {
                $caster = $caster->cast(...);
            }

            return collect(Arr::map(
                array: $this->items,
                callback: function ($value, $key) use ($caster, $callback, $expectedType) {
                    return $callback($caster($value, $expectedType->getName()), $key);
                },
            ));
        };
    }
}
