<?php

use Carbon\Carbon;
use dhy\LaravelMapWithCastMacro\MapWithCast;
use dhy\LaravelMapWithCastMacro\MapWithCastMacroServiceProvider;
use Illuminate\Support\Collection;
use Illuminate\Support\Fluent;
use Illuminate\Support\Uri;
use Orchestra\Testbench\Concerns\CreatesApplication;
use PhpBench\Attributes as Bench;

class MapWithCastBench
{
    use CreatesApplication;

    protected Collection $data;

    public function __construct()
    {
        $this->createApplication();
    }

    public function setUp(): void
    {
        app(MapWithCast::class)->clearCache();
    }

    public function setUpScalar(): void
    {
        $this->data = collect(range(0, 1_000_000));
    }

    public function setUpUri(): void
    {
        $this->data = collect(array_fill(
            start_index: 0,
            count: 100_000,
            value: 'https://username:password@example.com:1234/path/to/resource?a=b&x=y#fragment',
        ));
    }

    public function setUpFluent(): void
    {
        $this->data = collect(array_fill(
            start_index: 0,
            count: 100_000,
            value: ['key' => 'value'],
        ));
    }

    public function setUpCarbon(): void
    {
        $this->data = collect(array_fill(
            start_index: 0,
            count: 100_000,
            value: '2025-03-15 12:30:45',
        ));
    }

    #[Bench\BeforeMethods(['setUp', 'setUpScalar'])]
    #[Bench\Revs(10)]
    #[Bench\Iterations(1)]
    public function bench_scalar_mapWithCast(): void
    {
        $this->data->mapWithCast(fn (string $value) => $value);
    }

    #[Bench\BeforeMethods(['setUp', 'setUpScalar'])]
    #[Bench\Revs(10)]
    #[Bench\Iterations(1)]
    public function bench_scalar_map(): void
    {
        $this->data->map(fn (int $value, int $index) => (string) $value);
    }

    #[Bench\BeforeMethods(['setUp', 'setUpScalar'])]
    #[Bench\Revs(10)]
    #[Bench\Iterations(1)]
    public function bench_scalar_intermediate_map(): void
    {
        $this->data
            ->map(fn (int $value, int $index) => (string) $value)
            ->map(fn (string $value, int $index) => $value);
    }

    #[Bench\BeforeMethods(['setUp', 'setUpUri'])]
    #[Bench\Revs(10)]
    #[Bench\Iterations(1)]
    public function bench_uri_mapWithCast(): void
    {
        $this->data->mapWithCast(fn (Uri $uri) => $uri->fragment());
    }

    #[Bench\BeforeMethods(['setUp', 'setUpUri'])]
    #[Bench\Revs(10)]
    #[Bench\Iterations(1)]
    public function bench_uri_map(): void
    {
        $this->data->map(fn (string $value, int $index) => Uri::of($value)->fragment());
    }

    #[Bench\BeforeMethods(['setUp', 'setUpUri'])]
    #[Bench\Revs(10)]
    #[Bench\Iterations(1)]
    public function bench_uri_intermediate_map(): void
    {
        $this->data
            ->map(fn (string $value, int $index) => Uri::of($value))
            ->map(fn (Uri $uri, int $index) => $uri->fragment());
    }

    #[Bench\BeforeMethods(['setUp', 'setUpFluent'])]
    #[Bench\Revs(10)]
    #[Bench\Iterations(1)]
    public function bench_fluent_mapWithCast(): void
    {
        $this->data->mapWithCast(fn (Fluent $fluent) => $fluent->key);
    }

    #[Bench\BeforeMethods(['setUp', 'setUpFluent'])]
    #[Bench\Revs(10)]
    #[Bench\Iterations(1)]
    public function bench_fluent_map(): void
    {
        $this->data->map(fn (array $value, int $index) => fluent($value)->key);
    }

    #[Bench\BeforeMethods(['setUp', 'setUpFluent'])]
    #[Bench\Revs(10)]
    #[Bench\Iterations(1)]
    public function bench_fluent_intermediate_map(): void
    {
        $this->data
            ->map(fn (array $value, int $index) => fluent($value))
            ->map(fn (Fluent $fluent, int $index) => $fluent->key);
    }

    #[Bench\BeforeMethods(['setUp', 'setUpCarbon'])]
    #[Bench\Revs(10)]
    #[Bench\Iterations(1)]
    public function bench_carbon_mapWithCast(): void
    {
        $this->data->mapWithCast(fn (Carbon $carbon) => $carbon->toDateTimeString());
    }

    #[Bench\BeforeMethods(['setUp', 'setUpCarbon'])]
    #[Bench\Revs(10)]
    #[Bench\Iterations(1)]
    public function bench_carbon_map(): void
    {
        $this->data->map(fn (string $value, int $index) => (new Carbon($value))->toDateTimeString());
    }

    #[Bench\BeforeMethods(['setUp', 'setUpCarbon'])]
    #[Bench\Revs(10)]
    #[Bench\Iterations(1)]
    public function bench_carbon_intermediate_map(): void
    {
        $this->data
            ->map(fn (string $value) => new Carbon($value))
            ->map(fn (Carbon $carbon, int $index) => $carbon->toDateTimeString());
    }

    protected function getPackageProviders($app): array
    {
        return [
            MapWithCastMacroServiceProvider::class,
        ];
    }
}
