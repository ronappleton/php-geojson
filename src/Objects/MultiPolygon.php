<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use RonAppleton\GeoJson\Abstracts\GeoJsonObject;

use function array_map;
use function array_merge;

class MultiPolygon extends GeoJsonObject
{
    /**
     * @var array<int, Polygon>
     */
    private array $polygons = [];

    /**
     * @return array<int, Polygon>
     */
    public function getPolygons(): array
    {
        return $this->polygons;
    }

    public function setPolygons(Polygon ... $polygons): static
    {
        $this->polygons = array_merge($this->polygons, $polygons);

        return $this;
    }

    /**
     * @return array<int, array<int, array<int, array<int, float>>>>
     */
    public function coordinates(): array
    {
        return array_map(static fn (Polygon $polygon) => $polygon->coordinates(), $this->polygons);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->geometryArray($this->coordinates());
    }
}
