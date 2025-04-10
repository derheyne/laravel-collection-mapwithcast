<?php

namespace dhy\LaravelMapWithCastMacro;

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
}
