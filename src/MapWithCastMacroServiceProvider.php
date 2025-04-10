<?php

namespace dhy\LaravelMapWithCastMacro;

use dhy\LaravelMapWithCastMacro\Macro\MapWithCastMacro;
use Illuminate\Support\Collection;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MapWithCastMacroServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-collection-mapwithcast')
            ->hasConfigFile(configFileName: 'mapwithcast');
    }

    public function packageRegistered(): void
    {
        Collection::macro(name: 'mapWithCast', macro: app(MapWithCastMacro::class)());
    }
}
