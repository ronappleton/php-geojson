<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use JsonException;
use RonAppleton\GeoJson\Abstracts\GeoJsonObject;
use RonAppleton\GeoJson\Interfaces\Arrayable;

class Point extends GeoJsonObject implements Arrayable
{
    private float $longitude;
    private float $latitude;
    
    public function getLongitude(): float
    {
        return $this->longitude;
    }
    
    public function setLongitude(float $longitude): Point
    {
        $this->longitude = $longitude;
        
        return $this;
    }
    
    public function getLatitude(): float
    {
        return $this->latitude;
    }
    
    public function setLatitude(float $latitude): Point
    {
        $this->latitude = $latitude;
        
        return $this;
    }
    
    public function setPoints(float $longitude, float $latitude): Point
    {
        $this->longitude = $longitude;
        $this->latitude = $longitude;
        
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
        return [$this->longitude, $this->latitude];
    }
}
