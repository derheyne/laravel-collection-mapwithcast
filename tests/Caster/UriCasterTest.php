<?php

use dhy\LaravelMapWithCastMacro\Caster\UriCaster;
use Illuminate\Support\Collection;
use Illuminate\Support\Uri;

beforeEach(function () {
    $this->caster = new UriCaster;
});

it('casts a value into an Uri object', function (string $class, $value) {
    expect($this->caster->cast(value: $value, type: $class))
        ->toBeInstanceOf($class)
        ->scheme()->toBe('https')
        ->user()->toBe('username')
        ->password()->toBe('password')
        ->host()->toBe('example.com')
        ->port()->toBe(1234)
        ->path()->toBe('path/to/resource')
        ->query()->value()->toBe('a=b&x=y')
        ->fragment()->toBe('fragment');
})->with([
    'uri' => Uri::class,
    'child uri' => ChildUri::class,
], [
    'uri string' => 'https://username:password@example.com:1234/path/to/resource?a=b&x=y#fragment',
    'UriInterface' => Uri::of('https://username:password@example.com:1234/path/to/resource?a=b&x=y#fragment')->getUri(),
    'Stringable' => str('https://username:password@example.com:1234/path/to/resource?a=b&x=y#fragment'),
]);

it('fails if the value is not compatible with Uri::__construct', function ($value) {
    $this->expectExceptionObject(new InvalidArgumentException('Value for type [Illuminate\Support\Uri] must be compatible with Uri::__construct.'));

    $this->caster->cast(value: $value, type: Uri::class);
})->with([
    'array' => [[]],
    'object' => (object) [],
    'number' => 123,
]);

it('can identify whether the caster qualifies for the given type', function ($type, $result) {
    expect($this->caster->qualifies($type))->toBe($result);
})->with([
    'uri' => [Uri::class, true],
    'inherited uri' => [ChildUri::class, true],
    'foreign class' => [Collection::class, false],
    'string' => ['string', false],
    'number' => [123, false],
    'array' => [[], false],
]);
