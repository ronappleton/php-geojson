<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use JsonException;
use RonAppleton\GeoJson\Abstracts\GeoJsonObject;
use RonAppleton\GeoJson\Interfaces\Arrayable;

use function json_encode;
use function array_map;

use const JSON_THROW_ON_ERROR;

class MultiPoint extends GeoJsonObject implements Arrayable
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
        return array_map(static fn (Point $point) => $point->toArray(), $this->points);
    }
}
