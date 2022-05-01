<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use JsonException;
use RonAppleton\GeoJson\Abstracts\GeoJsonObject;
use RonAppleton\GeoJson\Interfaces\Arrayable;

class MultiPoint extends GeoJsonObject implements Arrayable
{
    private array $points;
    
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

    public function toArray(): array
    {
        return array_map(static fn ($point) => $point->toArray(), $this->points);
    }
}
