<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use RonAppleton\GeoJson\Abstracts\GeoJsonObject;

use function array_merge;
use function array_map;

/**
 * @phpcs:disable SlevomatCodingStandard.Commenting.RequireOneLinePropertyDocComment.MultiLinePropertyComment
 */
class MultiPolygon extends GeoJsonObject
{
    /**
     * @var array<int, Polygon>
     */
    private array $polygons;

    /**
     * @return array<int, Polygon>
     */
    public function getPolygons(): array
    {
        return $this->polygons;
    }

    /**
     * @param array<int, Polygon> $polygons
     */
    public function setPolygons(Polygon ... $polygons): MultiPolygon
    {
        $this->polygons = array_merge($this->polygons ?? [], $polygons);
        
        return $this;
    }

    /**
     * @return array<int, mixed>
     */
    public function toArray(): array
    {
        return array_map(static fn (Polygon $polygon) => $polygon->toArray(), $this->polygons);
    }
}
