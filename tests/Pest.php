<?php

use Carbon\Carbon;
use dhy\LaravelMapWithCastMacro\Tests\TestCase;
use Illuminate\Support\Carbon as LaravelCarbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;
use Illuminate\Support\Optional;
use Illuminate\Support\Stringable;
use Illuminate\Support\Uri;

uses(TestCase::class)->in(__DIR__);

class ChildFluent extends Fluent {}

class ChildStringable extends Stringable {}

class ChildCollection extends Collection {}

class ChildOptional extends Optional {}

class ChildUri extends Uri {}

class ChildCarbon extends Carbon {}

class ChildLaravelCarbon extends LaravelCarbon {}

class UnableToCastClass {}

class CustomDataObject
{
    public function __construct(
        public string $value,
    ) {}
}
