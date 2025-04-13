<?php

namespace dhy\LaravelMapWithCastMacro\Mixin;

use dhy\LaravelMapWithCastMacro\MapWithCast;
use Illuminate\Support\Collection;

/** @mixin Collection */
class MapWithCastMixin
{
    public function mapWithCast(): callable
    {
        return function (callable $callback, ?callable $caster = null): Collection {
            /** @var Collection $this */
            return app(MapWithCast::class)->apply($this, $callback, $caster);
        };
    }
}
