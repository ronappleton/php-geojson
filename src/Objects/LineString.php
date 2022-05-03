<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use RonAppleton\GeoJson\Abstracts\GeoJsonObject;
use RonAppleton\GeoJson\Exceptions\NotEnoughPoints;

use function array_merge;
use function count;
use function array_map;

class LineString extends GeoJsonObject
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

    /**
     * @param array<int, Point> $points
     */
    public function addPoints(Point ... $points): LineString
    {
        $this->points = array_merge($this->points ?? [], $points);
        
        return $this;
    }
    
    public function addPoint(Point $point): LineString
    {
        $this->points[] = $point;
        
        return $this;
    }

    /**
     * @return array<int, array<float, float>>
     */
    public function toArray(): array
    {
        if (count($this->points) < 2)
        {
            throw new NotEnoughPoints(count($this->points), 2);
        }
        
        return array_map(static fn (Point $point) => $point->toArray(), $this->points);
    }
}
