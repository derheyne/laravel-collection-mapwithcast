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
    'stringable' => Stringable::class,
    'inherited stringable' => ChildStringable::class,
], [
    'string' => 'string',
    'number' => 123,
    'laravel stringable' => new Stringable('string'),
    'native stringable' => new class implements BaseStringable
    {
        public function __toString()
        {
            return 'hello world';
        }
    },
]);

it('fails if the value is not compatible with Stringable::__construct', function ($value) {
    $this->expectExceptionObject(new InvalidArgumentException('Value for type ['.Stringable::class.'] must be stringable.'));

    $this->caster->cast(value: $value, type: Stringable::class);
})->with([
    'array' => [['foo', 'bar']],
    'object' => (object) ['foo', 'bar'],
]);

it('can identify whether the caster qualifies for the given type', function ($type, $result) {
    expect($this->caster->qualifies($type))->toBe($result);
})->with([
    'stringable' => [Stringable::class, true],
    'inherited stringable' => [ChildStringable::class, true],
    'foreign class' => [Collection::class, false],
    'string' => ['string', false],
    'number' => [123, false],
    'array' => [[], false],
]);
