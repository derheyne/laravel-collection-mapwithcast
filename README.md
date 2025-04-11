# Laravel Collection Macro: `mapWithCast`

[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/derheyne/laravel-collection-mapwithcast/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/derheyne/laravel-collection-mapwithcast/actions?query=workflow%3Arun-tests+branch%3Amain)

Automatically cast values in Laravel collections when using `mapWithCast` with typed closures.

```php
collect([['name' => 'John'], ['name' => 'Jane']])
    ->mapWithCast(fn (Fluent $data) => $data->name)

// Result: ['John', 'Jane']
```

## 📦 About

`mapWithCast` is a Laravel collection macro that enhances the map method by automatically casting each item in the
collection to the type hinted in your closure. It saves you from manual casting and enforces better type safety, making
your code cleaner and more expressive.

It supports both **scalar types** like `int` and `string` and complex Laravel-specific types like `Collection`, `Fluent`, and
`Stringable`.

## 🚀 Installation
Install the package via Composer:

```shell
composer require derheyne/laravel-map-with-cast
```

The macro will be automatically registered thanks to Laravel's package discovery.

## 🧠 Type Support

### ✅ Supported Types (Out of the Box)

* `int`
* `float`
* `bool`
* `string`
* `array`
* `object`
* `Illuminate\Support\Carbon` and `Carbon\Carbon`
* `Illuminate\Support\Collection`
* `Illuminate\Support\Fluent`
* `Illuminate\Support\Optional`
* `Illuminate\Support\Stringable`
* `Illuminate\Support\Uri`

### ⚙️ Extending with Custom Casters
Need to handle your own types or custom logic? You can register additional casters by publishing the config file:

```shell
php artisan vendor:publish --tag=laravel-collection-mapwithcast-config
```

This will create a config file where you can specify your own custom casters:

```php
// config/mapwithcast.php

return [
    'casters' => [
        App\Caster\SpatieLaravelDataCaster::class
    ],
];
```

```php
namespace App\Casters;

use dhy\LaravelMapWithCastMacro\Contract\Caster;
use Spatie\LaravelData\Data;

class SpatieLaravelDataCaster implements Caster
{
    public function qualifies(mixed $type): bool
    {
        if (! is_string($type)) {
            return false;
        }

        return is_subclass_of(object_or_class: $type, class: Data::class, allow_string: true);
    }

    /** @param  Data  $type */
    public function cast(mixed $value, mixed $type): Data
    {
        return $type::from($value);
    }
}
```

Now you can automatically cast the value into the specified laravel-data DTO:

```php
use Spatie\LaravelData\Data;

class CustomerData extends Data {
    public function __construct(
        public string $prename,
        public string $surname,
        public string $city,
    ) {}   
}
collect([['prename' => 'Jane', 'surname' => 'Doe', 'city' => 'New York']])
    ->mapWithCast(fn (CustomerData $customer) => $customer->prename.' '.$customer->surname)

// Returns: ['Jane Doe']
```

## 📚 Examples
### 🧮 Convert and Process Numbers

```php
$totals = collect(['10.50', '20.75', '30'])
    ->mapWithCast(fn (float $price) => $price * 1.2);

// Result: [12.6, 24.9, 36.0]
```

### 🧠 Cast to Laravel `Collection`

```php
$sums = collect([[1, 2, 3], [4, 5, 6]])
    ->mapWithCast(fn (Collection $items) => $items->sum());

// Result: [6, 15]
```

### 🔄 Cast to Stringable

```php
$slugs = collect(['Laravel Tips', 'PHP Tricks'])
    ->mapWithCast(fn (Stringable $str) => $str->slug());

// Result: ['laravel-tips', 'php-tricks']
```

### 🪡 Cast by specifying a custom caster

```php
class CustomDataObject
{
    public function __construct(
        public string $value,
    ) {}
}

collect(['one', 'two', 'three'])
    ->mapWithCast(
        callback: fn (CustomDataObject $value) => 'Value: '.$value->value,
        caster: fn ($value, $type) => new $type($value),
    );

// Return ['Value: one', 'Value: two', 'Value: three']
```

### ✅ Testing

```shell
composer test
```

## 🧪 Compatibility
* Laravel 11.x, 12.x
* PHP 8.3+

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
