<?php

use dhy\LaravelMapWithCastMacro\Caster\OptionalCaster;
use Illuminate\Support\Collection;
use Illuminate\Support\Optional;

beforeEach(function () {
    $this->caster = new OptionalCaster;
});

it('casts a value into an Optional object', function (string $class) {
    expect($this->caster->cast(value: (object) ['foo' => 'value', 'bar' => 123], type: $class))
        ->toBeInstanceOf($class)
        ->foo->toBe('value')
        ->bar->toBe(123);
})->with([
    'optional' => Optional::class,
    'inherited optional' => ChildOptional::class,
]);

it('can identify whether the caster qualifies for the given type', function ($type, $result) {
    expect($this->caster->qualifies($type))->toBe($result);
})->with([
    'optional' => [Optional::class, true],
    'inherited optional' => [ChildOptional::class, true],
    'foreign class' => [Collection::class, false],
    'string' => ['string', false],
    'number' => [123, false],
    'array' => [[], false],
]);
