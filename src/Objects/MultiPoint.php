<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use RonAppleton\GeoJson\Abstracts\GeoJsonObject;

use function array_map;

class MultiPoint extends GeoJsonObject
{
    /**
     * @var array<int, Point>
     */
    private array $points;

    /**
     * @return array<int, Point>
     */
    public function getPoints(): array
    {
        return $this->points;
    }
    
    public function addPoint(Point $point): MultiPoint
    {
        $this->points[] = $point;
        
        return $this;
    }

    /**
     * @return array<int, array<int, float>>
     */
    public function toArray(): array
    {
        return array_map(static fn (Point $point) => $point->toArray(), $this->points);
    }
}
