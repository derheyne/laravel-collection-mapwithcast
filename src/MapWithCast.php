<?php

namespace dhy\LaravelMapWithCastMacro;

use dhy\LaravelMapWithCastMacro\Contract\Caster;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use ReflectionFunction;
use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionUnionType;
use RuntimeException;

class MapWithCast
{
    protected array $casterCache = [];

    public function apply(Collection $collection, callable $callback, ?callable $customCaster = null): Collection
    {
        [$caster, $type] = $this->prepareMappingContext($callback, $customCaster);

        if (! $caster) {
            return $collection->map($callback);
        }

        return $collection->map(fn ($value, $key) => $callback($caster($value, $type), $key));
    }

    protected function prepareMappingContext(callable $callback, ?callable $customCaster = null): ?array
    {
        $targetType = $this->getTargetType($callback);

        if (! $targetType && ! $customCaster) {
            return null;
        }

        $typeName = $targetType->getName();
        $resolvedCaster = $customCaster ?? $this->resolveCasterForType($typeName);

        return [$resolvedCaster, $typeName];
    }

    protected function getTargetType(callable $callback): ?ReflectionNamedType
    {
        $parameters = (new ReflectionFunction($callback(...)))->getParameters();

        if (! $parameters) {
            return null;
        }

        $type = Arr::first($parameters)?->getType();

        if (! $type) {
            return null;
        }

        if ($type instanceof ReflectionIntersectionType || $type instanceof ReflectionUnionType) {
            throw new InvalidArgumentException('Cannot resolve value type from union or intersection type.');
        }

        return $type;
    }

    protected function resolveCasterForType(string $type): callable
    {
        if (isset($this->casterCache[$type])) {
            return $this->casterCache[$type];
        }

        $caster = $this->findCasterForType($type);

        if (! $caster) {
            throw new InvalidArgumentException("No caster configured for type [{$type}].");
        }

        return $this->casterCache[$type] = $caster->cast(...);
    }

    protected function findCasterForType(string $typeName): ?object
    {
        $configuredCasters = config('mapwithcast.casters', []);

        foreach ($configuredCasters as $casterClass) {
            try {
                $caster = app()->make($casterClass);
            } catch (BindingResolutionException) {
                continue;
            }

            if (! $caster instanceof Caster) {
                throw new RuntimeException("Caster [{$casterClass}] must implement Caster interface.");
            }

            if ($caster->qualifies($typeName)) {
                return $caster;
            }
        }

        return null;
    }

    public function clearCache(): void
    {
        $this->casterCache = [];
    }
}
