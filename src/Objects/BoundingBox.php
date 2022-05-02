<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use JsonException;
use RonAppleton\GeoJson\Abstracts\GeoJsonObject;
use RonAppleton\GeoJson\Exceptions\TooManyPoints;
use RonAppleton\GeoJson\Interfaces\Arrayable;

class BoundingBox extends GeoJsonObject implements Arrayable
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
     * @throws JsonException
     */
    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
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
