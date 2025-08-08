<?php

namespace AryaSvitkona\MetricSatellite;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use AryaSvitkona\MetricSatellite\Http\Middleware\VerifySatelliteSignature;

class MetricSatelliteServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/metric-satellite.php', 'metric-satellite');
    }

    public function boot(): void
    {
        $router = $this->app['router'];
        $router->aliasMiddleware('satellite.signature', VerifySatelliteSignature::class);

        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        $this->publishes([
            __DIR__.'/../config/metric-satellite.php' => config_path('metric-satellite.php'),
        ], 'metric-satellite-config');
    }
}
