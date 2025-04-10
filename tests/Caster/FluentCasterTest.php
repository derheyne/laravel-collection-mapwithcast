<?php

use dhy\LaravelMapWithCastMacro\Caster\FluentCaster;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;

beforeEach(function () {
    $this->caster = new FluentCaster;
});

it('can cast a value into a Fluent object', function ($value) {
    expect($this->caster->cast(value: $value, type: Fluent::class))
        ->toBeInstanceOf(Fluent::class);
})->with([
    'collection' => [new Collection(['foo', 'bar'])],
    'array' => [['foo', 'bar']],
    'fluent' => [new Fluent(['foo', 'bar'])],
]);

it('can cast a value into a custom Fluent object', function () {
    expect($this->caster->cast(value: ['foo', 'bar'], type: ChildFluent::class))
        ->toBeInstanceOf(ChildFluent::class);
});

it('fails if the value is not an iterable', function ($value) {
    $this->expectExceptionObject(new InvalidArgumentException('Value for type ['.Fluent::class.'] must be iterable.'));

    $this->caster->cast(value: $value, type: Fluent::class);
})->with([
    'string' => ['invalid'],
    'number' => 123,
]);

it('can identify whether the caster qualifies for the given type', function ($type, $result) {
    expect($this->caster->qualifies($type))->toBe($result);
})->with([
    'fluent' => [Fluent::class, true],
    'inherited fluent' => [ChildFluent::class, true],
    'collection' => [Collection::class, false],
    'array' => ['array', false],
    'string' => ['invalid', false],
    'number' => [123, false],
]);
