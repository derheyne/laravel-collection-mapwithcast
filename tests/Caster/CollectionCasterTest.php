<?php

use dhy\LaravelMapWithCastMacro\Caster\CollectionCaster;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;

beforeEach(function () {
    $this->caster = new CollectionCaster;
});

it('casts a value into a collection', function () {
    expect($this->caster->cast(value: ['foo', 'bar'], type: Collection::class))
        ->toBeInstanceOf(Collection::class)
        ->toContain('foo', 'bar');
})->with([
    'collection' => Collection::class,
    'inherited collection' => ChildCollection::class,
]);

it('can identify whether the caster qualifies for the given type', function ($type, $result) {
    expect($this->caster->qualifies($type))->toBe($result);
})->with([
    'collection' => [Collection::class, true],
    'inherited collection' => [ChildCollection::class, true],
    'foreign class' => [Fluent::class, false],
    'string' => ['string', false],
    'number' => [123, false],
    'array' => [[], false],
]);
