<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use JsonException;
use RonAppleton\GeoJson\Abstracts\GeoJsonObject;
use RonAppleton\GeoJson\Interfaces\Arrayable;

class MultiPolygon extends GeoJsonObject implements Arrayable
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
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<int, array<int, float>
     */
    public function toArray(): array
    {
        return array_map(static fn (Polygon $polygon) => $polygon->toArray(), $this->polygons);
    }
}
