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
