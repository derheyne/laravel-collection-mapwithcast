<?php

use dhy\LaravelMapWithCastMacro\Caster\FluentCaster;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;

beforeEach(function () {
    $this->caster = new FluentCaster;
});

it('can cast a value into a Fluent object', function (string $class, $value) {
    expect($this->caster->cast(value: $value, type: $class))
        ->toBeInstanceOf($class);
})->with([
    'fluent' => Fluent::class,
    'inherited fluent' => ChildFluent::class,
], [
    'collection' => new Collection(['foo', 'bar']),
    'array' => [['foo', 'bar']],
    'fluent' => new Fluent(['foo', 'bar']),
]);

it('fails if the value is not compatible with Fluent::__construct', function ($value) {
    $this->expectExceptionObject(new InvalidArgumentException('Value for type ['.Fluent::class.'] must be iterable.'));

    $this->caster->cast(value: $value, type: Fluent::class);
})->with([
    'string' => 'invalid',
    'number' => 123,
]);

it('can identify whether the caster qualifies for the given type', function ($type, $result) {
    expect($this->caster->qualifies($type))->toBe($result);
})->with([
    'fluent' => [Fluent::class, true],
    'inherited fluent' => [ChildFluent::class, true],
    'foreign class' => [Collection::class, false],
    'string' => ['string', false],
    'number' => [123, false],
    'array' => [[], false],
]);
