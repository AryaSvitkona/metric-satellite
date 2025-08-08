<?php

use Illuminate\Support\Facades\Route;
use AryaSvitkona\MetricSatellite\Http\Controllers\SatelliteController;

Route::prefix(config('metric-satellite.route.prefix', 'metrics'))
    ->middleware(config('metric-satellite.route.middleware'))
    ->group(function () {
        Route::get('/satellite', SatelliteController::class)
            ->name(config('metric-satellite.route.name', 'metrics.satellite'));
    });