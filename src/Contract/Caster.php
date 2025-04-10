<?php

namespace dhy\LaravelMapWithCastMacro\Contract;

interface Caster
{
    public function qualifies(mixed $type): bool;

    public function cast(mixed $value, mixed $type): mixed;
}
