<?php

namespace dhy\LaravelMapWithCastMacro;

use dhy\LaravelMapWithCastMacro\Mixin\MapWithCastMixin;
use Illuminate\Foundation\Application;
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

    public function packageBooted(): void
    {
        $this->app->scoped(MapWithCast::class, fn (Application $app) => new MapWithCast($app));
    }

    public function packageRegistered(): void
    {
        Collection::mixin(new MapWithCastMixin);
    }
}
