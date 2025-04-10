<?php

use dhy\LaravelMapWithCastMacro\Caster\SimpleTypeCaster;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Constraint\IsType;

beforeEach(function () {
    $this->caster = new SimpleTypeCaster;
});

it('can identify whether the caster qualifies for the given type', function ($type, $result) {
    expect($this->caster->qualifies($type))->toBe($result);
})->with([
    'array' => ['array', true],
    'string' => ['string', true],
    'int' => ['int', true],
    'float' => ['float', true],
    'bool' => ['bool', true],
    'object' => ['object', true],
    'class' => [Collection::class, false],
    'anonymous class' => [(new class {})::class, false],
]);

it('can cast a value into a new simple type', function ($value, $to) {
    foreach ($to as $type) {
        $this->assertThat(
            value: $this->caster->cast($value, $type),
            constraint: new IsType($type),
        );
    }
})->with([
    'null' => [null, ['int', 'float', 'string', 'bool', 'array', 'object']],
    'int' => [1, ['int', 'float', 'string', 'bool', 'array', 'object']],
    'float' => [1.0, ['float', 'int', 'string', 'bool', 'array', 'object']],
    'string' => ['string', ['string', 'int', 'float', 'bool', 'array', 'object']],
    'bool' => [true, ['bool', 'int', 'float', 'string', 'array', 'object']],
    'array' => [['test' => 'value'], ['array', 'object']],
    'object' => [(object) ['test' => 'value'],  ['object', 'array']],
]);

it('fails if a value cannot be cast', function ($value, $to) {
    foreach ($to as $type) {
        $this->expectExceptionObject(new InvalidArgumentException('Value cannot be cast to type ['.$type.'].'));

        $this->caster->cast($value, $type);
    }
})->with([
    'array' => [['test' => 'value'], ['int', 'float', 'string', 'bool']],
    'object' => [(object) ['test' => 'value'],  ['int', 'float', 'string', 'bool']],
]);
