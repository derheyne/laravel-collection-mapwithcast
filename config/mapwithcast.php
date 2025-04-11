<?php

use dhy\LaravelMapWithCastMacro\Caster\CarbonCaster;
use dhy\LaravelMapWithCastMacro\Caster\CollectionCaster;
use dhy\LaravelMapWithCastMacro\Caster\FluentCaster;
use dhy\LaravelMapWithCastMacro\Caster\OptionalCaster;
use dhy\LaravelMapWithCastMacro\Caster\SimpleTypeCaster;
use dhy\LaravelMapWithCastMacro\Caster\StringableCaster;
use dhy\LaravelMapWithCastMacro\Caster\UriCaster;

return [
    'casters' => [
        SimpleTypeCaster::class,
        CollectionCaster::class,
        FluentCaster::class,
        CarbonCaster::class,
        UriCaster::class,
        OptionalCaster::class,
        StringableCaster::class,
    ],
];
