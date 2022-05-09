<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use RonAppleton\GeoJson\Abstracts\GeoJsonObject;
use RonAppleton\GeoJson\Enums\PointExceptionType;
use RonAppleton\GeoJson\Exceptions\Point as PointException;

class Point extends GeoJsonObject
{
    private float $longitude;
    
    private float $latitude;
    
    public function getLongitude(): float
    {
        return $this->longitude ?? throw new PointException(PointExceptionType::PointNotSet, 'longitude');
    }
    
    public function setLongitude(float $longitude): Point
    {
        if (isset($this->longitude)) {
            throw new PointException(PointExceptionType::PointSet, 'longitude');
        }
        
        $this->longitude = $longitude;
        
        return $this;
    }
    
    public function getLatitude(): float
    {
        return $this->latitude ?? throw new PointException(PointExceptionType::PointNotSet, 'latitude');
    }
    
    public function setLatitude(float $latitude): Point
    {
        if (isset($this->latitude)) {
            throw new PointException(PointExceptionType::PointSet, 'latitude');
        }
        
        $this->latitude = $latitude;
        
        return $this;
    }
    
    public function setPoints(float $longitude, float $latitude): Point
    {
        if (isset($this->longitude)) {
            throw new PointException(PointExceptionType::PointSet, 'longitude');
        }
        
        $this->longitude = $longitude;

        if (isset($this->latitude)) {
            throw new PointException(PointExceptionType::PointSet, 'latitude');
        }
        
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
