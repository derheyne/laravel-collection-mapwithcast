<?php

namespace dhy\LaravelMapWithCastMacro\Tests;

use dhy\LaravelMapWithCastMacro\MapWithCastMacroServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            MapWithCastMacroServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
    }
}
