<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;
use Illuminate\Support\Stringable;

it('retains native functionality if not type was specified', function () {
    $arr = [1, 2, 3];

    expect(collect($arr))
        ->mapWithCast(fn ($value) => $value * 2)
        ->toEqual(collect($arr)->map(fn ($value) => $value * 2));
});

it('can cast simple types', function () {
    expect(collect(['1', '2', '3']))
        ->mapWithCast(fn (int $value) => $value)
        ->toEqual(collect([1, 2, 3]));
});

it('can cast to collections', function () {
    expect(collect([['1', '2', '3']]))
        ->mapWithCast(fn (Collection $value) => $value)
        ->toContainOnlyInstancesOf(Collection::class);
});

it('can cast to inherited collections', function () {
    expect(collect([['1', '2', '3']]))
        ->mapWithCast(fn (ChildCollection $value) => $value)
        ->toContainOnlyInstancesOf(ChildCollection::class);
});

it('can cast to fluent', function () {
    expect(collect([['first' => 'value', 'second' => 'value']]))
        ->mapWithCast(fn (Fluent $value) => $value)
        ->toContainOnlyInstancesOf(Fluent::class);
});

it('can cast to stringable', function () {
    expect(collect(['one', 'two', 'three']))
        ->mapWithCast(fn (Stringable $value) => $value)
        ->toContainOnlyInstancesOf(Stringable::class);
});

it('can pass the index to the callback', function () {
    expect(collect(['1', '2', '3']))
        ->mapWithCast(fn (int $value, $index) => $value + $index)
        ->toContainEqual(1, 3, 5);
});

it('can pass the key to the callback', function () {
    expect(collect(['one' => '1', 'two' => '2', 'three' => '3']))
        ->mapWithCast(fn (int $value, $key) => $key.': '.$value)
        ->toContainEqual('one: 1', 'two: 2', 'three: 3');
});

it('can specify a custom caster', function () {
    expect(collect(['one', 'two', 'three'])->mapWithCast(
        callback: fn (CustomDataObject $value) => $value,
        caster: fn ($value, $type) => new $type($value),
    ))
        ->toContainOnlyInstancesOf(CustomDataObject::class);
});

it('throws an exception if it cannot find an appropriate caster', function () {
    $this->expectExceptionObject(new InvalidArgumentException('No caster configured for type [UnableToCastClass]'));

    collect(['one', 'two', 'three'])->mapWithCast(fn (UnableToCastClass $value) => $value);
});

it('throws an exception if a union or intersection type was used', function () {
    $this->expectExceptionObject(new InvalidArgumentException('Cannot resolve value type from union or intersection type.'));

    collect(['one', 'two', 'three'])->mapWithCast(fn (string|Fluent $value) => $value);
});
