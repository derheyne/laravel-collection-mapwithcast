<?php

use dhy\LaravelMapWithCastMacro\Caster\CollectionCaster;
use Illuminate\Support\Collection;

beforeEach(function () {
    $this->caster = new CollectionCaster;
});

it('casts a value into a collection', function () {
    expect($this->caster->cast(value: ['foo', 'bar'], type: Collection::class))
        ->toBeInstanceOf(Collection::class)
        ->toContain('foo', 'bar');
});

it('can cast a value into a custom collection', function () {
    expect($this->caster->cast(value: ['foo', 'bar'], type: ChildCollection::class))
        ->toBeInstanceOf(ChildCollection::class)
        ->toContain('foo', 'bar');
});

it('can identify whether the caster qualifies for the given type', function ($type, $result) {
    expect($this->caster->qualifies($type))->toBe($result);
})->with([
    'collection' => [Collection::class, true],
    'inherited collection' => [ChildCollection::class, true],
    'string' => ['string', false],
    'number' => ['int', false],
    'array' => ['array', false],
]);
