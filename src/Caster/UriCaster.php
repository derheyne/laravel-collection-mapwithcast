<?php

namespace dhy\LaravelMapWithCastMacro\Caster;

use dhy\LaravelMapWithCastMacro\Contract\Caster;
use Illuminate\Support\Uri;
use InvalidArgumentException;
use League\Uri\Contracts\UriInterface;
use Stringable;

class UriCaster implements Caster
{
    public function qualifies(mixed $type): bool
    {
        if (! is_string($type)) {
            return false;
        }

        return is_a(object_or_class: $type, class: Uri::class, allow_string: true);
    }

    /** @param  Uri  $type */
    public function cast(mixed $value, mixed $type): mixed
    {
        if (! is_string($value) && ! $value instanceof UriInterface && ! $value instanceof Stringable) {
            throw new InvalidArgumentException('Value for type ['.$type.'] must be compatible with Uri::__construct.');
        }

        return new $type($value);
    }
}
