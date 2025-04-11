<?php

use Carbon\Carbon;
use Illuminate\Support\Carbon as LaravelCarbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;
use Illuminate\Support\Optional;
use Illuminate\Support\Stringable;
use Illuminate\Support\Uri;

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

it('can cast to carbon', function () {
    expect(collect(['2025-03-15 12:30:45', '2024-06-25 15:45:00', '2023-09-30 18:00:15']))
        ->mapWithCast(fn (Carbon $value) => $value)
        ->toContainOnlyInstancesOf(Carbon::class);
});

it('can cast to Laravel carbon', function () {
    expect(collect(['2025-03-15 12:30:45', '2024-06-25 15:45:00', '2023-09-30 18:00:15']))
        ->mapWithCast(fn (LaravelCarbon $value) => $value)
        ->toContainOnlyInstancesOf(LaravelCarbon::class);
});

it('can cast to optional', function () {
    expect(collect([['key' => 'value'], (object) ['key' => 'value']]))
        ->mapWithCast(fn (Optional $value) => $value)
        ->toContainOnlyInstancesOf(Optional::class);
});

it('can cast to uri', function () {
    expect(collect(['https://example.com', Uri::of('https://google.com')->getUri(), str('https://php.net')]))
        ->mapWithCast(fn (Uri $value) => $value)
        ->toContainOnlyInstancesOf(Uri::class);
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
