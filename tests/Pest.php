<?php

use dhy\LaravelMapWithCastMacro\Tests\TestCase;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;
use Illuminate\Support\Stringable;

uses(TestCase::class)->in(__DIR__);


class ChildFluent extends Fluent {}

class ChildStringable extends Stringable {}

class ChildCollection extends Collection {}

class UnableToCastClass {}

class CustomDataObject
{
    public function __construct(
        public string $value,
    ) {}
}
