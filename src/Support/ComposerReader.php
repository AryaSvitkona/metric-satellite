<?php

namespace AryaSvitkona\MetricSatellite\Support;

class ComposerReader
{
    public static function readLockFile(): array
    {
        $lockPath = base_path('composer.lock');
        if (! is_file($lockPath)) return [];

        $data = json_decode((string) file_get_contents($lockPath), true);
        $packages = $data['packages'] ?? [];
        $devPackages = $data['packages-dev'] ?? [];

        $toMap = fn($dependency) => [
            'name'    => $dependency['name'] ?? null,
            'version' => $dependency['version'] ?? null,
        ];

        return [
            'packages'     => array_map($toMap, $packages),
            'packages_dev' => array_map($toMap, $devPackages),
        ];
    }
}
