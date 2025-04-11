<?php

use dhy\LaravelMapWithCastMacro\Caster\CollectionCaster;
use dhy\LaravelMapWithCastMacro\Caster\FluentCaster;
use dhy\LaravelMapWithCastMacro\Caster\SimpleTypeCaster;
use dhy\LaravelMapWithCastMacro\Caster\StringableCaster;

return [
    'casters' => [
        SimpleTypeCaster::class,
        CollectionCaster::class,
        FluentCaster::class,
        StringableCaster::class,
    ],
];
