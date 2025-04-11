<?php

use Carbon\Carbon;
use Carbon\Month;
use Carbon\WeekDay;
use dhy\LaravelMapWithCastMacro\Caster\CarbonCaster;
use Illuminate\Support\Carbon as LaravelCarbon;
use Illuminate\Support\Collection;

beforeEach(function () {
    $this->caster = new CarbonCaster;
});

it('can cast a value into a Carbon object', function ($class, $value) {
    expect($this->caster->cast(value: $value, type: $class))
        ->toBeInstanceOf($class);
})->with([
    'carbon' => Carbon::class,
    'laravel carbon' => LaravelCarbon::class,
    'inherited carbon' => ChildCarbon::class,
    'inherited laravel carbon' => ChildLaravelCarbon::class,
], [
    'string' => '2025-03-15 12:30:45',
    'weekday' => WeekDay::Monday,
    'month' => Month::January,
    'int' => 1742041845,
    'float' => 1742041845.123456,
    'null' => null,
]);

it('fails if the value is not compatible with Carbon::__construct', function ($type, $value) {
    $this->expectExceptionObject(new InvalidArgumentException('Value for type ['.$type.'] must be compatible with Carbon::__construct.'));

    $this->caster->cast($value, $type);
})->with([
    'carbon' => Carbon::class,
    'laravel carbon' => LaravelCarbon::class,
], [
    'array' => [[]],
    'object' => (object) [],
    'collection' => collect(),
]);

it('can identify whether the caster qualifies for the given type', function ($type, $result) {
    expect($this->caster->qualifies($type))->toBe($result);
})->with([
    'collection' => [Carbon::class, true],
    'inherited collection' => [LaravelCarbon::class, true],
    'foreign class' => [Collection::class, false],
    'string' => ['string', false],
    'number' => [123, false],
    'array' => [[], false],
]);
