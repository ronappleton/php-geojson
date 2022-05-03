<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use RonAppleton\GeoJson\Abstracts\GeoJsonObject;

class MultiPolygon extends GeoJsonObject
{
    /**
     * @var array<int, Polygon>
     */
    private array $polygons;

    /**
     * @return Polygon[]
     */
    public function getPolygons(): array
    {
        return $this->polygons;
    }

    /**
     * @param array<int, Polygon> $polygons
     * @return MultiPolygon
     */
    public function setPolygons(Polygon ... $polygons): MultiPolygon
    {
        $this->polygons = array_merge($this->polygons ?? [], $polygons);
        
        return $this;
    }

    /**
     * @return array<int, array<int, float>
     */
    public function toArray(): array
    {
        return array_map(static fn (Polygon $polygon) => $polygon->toArray(), $this->polygons);
    }
}
