<?php

namespace AryaSvitkona\MetricSatellite\Http\Controllers;

use Illuminate\Foundation\Application;
use Illuminate\Routing\Controller;
use AryaSvitkona\MetricSatellite\Support\ComposerReader;

class SatelliteController extends Controller
{
    public function __invoke()
    {
        $composer = ComposerReader::readLockFile();

        return response()->json([
            'satellite' => [
                'app_name'        => config('app.name'),
                'environment'     => app()->environment(),
                'php_version'     => PHP_VERSION,
                'laravel_version' => Application::VERSION,
                'timezone'        => config('app.timezone'),
            ],
            'dependencies' => $composer,
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
