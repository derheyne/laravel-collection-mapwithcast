<?php

use dhy\LaravelMapWithCastMacro\Caster\StringableCaster;
use Illuminate\Support\Collection;
use Illuminate\Support\Stringable;
use Stringable as BaseStringable;

beforeEach(function () {
    $this->caster = new StringableCaster;
});

it('can cast a value into a Stringable object', function ($value) {
    expect($this->caster->cast(value: $value, type: Stringable::class))
        ->toBeInstanceOf(Stringable::class);
})->with([
    'string' => ['string'],
    'number' => [123],
    'laravel stringable' => new Stringable('string'),
    'native stringable' => new class implements BaseStringable {
        public function __toString()
        {
            return 'hello world';
        }
    },
]);

it('can cast a value into a custom Stringable object', function () {
    expect($this->caster->cast(value: 'string', type: ChildStringable::class))
        ->toBeInstanceOf(ChildStringable::class);
});

it('fails if the value is not stringable', function ($value) {
    $this->expectExceptionObject(new InvalidArgumentException('Value for type ['.Stringable::class.'] must be stringable.'));

    $this->caster->cast(value: $value, type: Stringable::class);
})->with([
    'array' => [['foo', 'bar']],
    'object' => [(object) ['foo', 'bar']],
]);

it('can identify whether the caster qualifies for the given type', function ($type, $result) {
    expect($this->caster->qualifies($type))->toBe($result);
})->with([
    'stringable' => [Stringable::class, true],
    'inherited stringable' => [ChildStringable::class, true],
    'collection' => [Collection::class, false],
    'array' => ['array', false],
    'string' => ['invalid', false],
    'number' => [123, false],
]);
