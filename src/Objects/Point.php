<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use RonAppleton\GeoJson\Abstracts\GeoJsonObject;

class Point extends GeoJsonObject
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
        $this->latitude = $latitude;
        
        return $this;
    }

    /**
     * @return array<int, float>
     */
    public function toArray(): array
    {
        return [$this->longitude, $this->latitude];
    }
}
