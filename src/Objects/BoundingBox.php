<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use RonAppleton\GeoJson\Abstracts\GeoJsonObject;

class BoundingBox extends GeoJsonObject
{
    /**
     * @var array<int, float>
     */
    private array $points;

    /**
     * @return array<int, float>
     */
    public function getPoints(): array
    {
        return $this->points;
    }
    
    public function setPoints(Point $southwest, Point $northeast): BoundingBox
    {
        $this->points = [$southwest, $northeast];
        
        return $this;
    }

    /**
     * @return array<int, array<int, float>>
     */
    public function toArray(): array
    {
        $pointsMap = array_map(static fn (Point $point) => $point->toArray(), $this->points);
        
        return array_merge(... $pointsMap);
    }
}
