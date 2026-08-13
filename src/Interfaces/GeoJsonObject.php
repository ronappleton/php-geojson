<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Interfaces;

use RonAppleton\GeoJson\Enums\GeoJsonType;

interface GeoJsonObject
{
    public function getType(): GeoJsonType;

    /**
     * @return array<int|string, mixed>
     */
    public function toArray(): array;
}
