<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use JsonException;
use RonAppleton\GeoJson\Abstracts\GeoJsonObject;
use RonAppleton\GeoJson\Exceptions\NotEnoughPoints;
use RonAppleton\GeoJson\Interfaces\Arrayable;

use function array_merge;
use function json_encode;
use function count;
use function array_map;

use const JSON_THROW_ON_ERROR;

class LineString extends GeoJsonObject implements Arrayable
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
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
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
