<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use RonAppleton\GeoJson\Abstracts\GeoJsonObject;
use RonAppleton\GeoJson\Enums\PointExceptionType;
use RonAppleton\GeoJson\Exceptions\Point as PointException;

class Point extends GeoJsonObject
{
    private const float MIN_LONGITUDE = - 180.0;

    private const float MAX_LONGITUDE = 180.0;

    private const float MIN_LATITUDE = - 90.0;

    private const float MAX_LATITUDE = 90.0;

    private ?float $longitude = null;

    private ?float $latitude = null;

    private ?float $altitude = null;

    public function setPoints(float $longitude, float $latitude): static
    {
        if ($this->longitude !== null || $this->latitude !== null) {
            throw new PointException(PointExceptionType::PointSet, 'longitude');
        }

        $this->setLongitude($longitude);
        $this->setLatitude($latitude);

        return $this;
    }

    public function setLongitude(float $longitude): static
    {
        if ($this->longitude !== null) {
            throw new PointException(PointExceptionType::PointSet, 'longitude');
        }

        if ($longitude < self::MIN_LONGITUDE || $longitude > self::MAX_LONGITUDE) {
            throw new PointException(PointExceptionType::InvalidLongitude, (string) $longitude);
        }

        $this->longitude = $longitude;

        return $this;
    }

    public function setLatitude(float $latitude): static
    {
        if ($this->latitude !== null) {
            throw new PointException(PointExceptionType::PointSet, 'latitude');
        }

        if ($latitude < self::MIN_LATITUDE || $latitude > self::MAX_LATITUDE) {
            throw new PointException(PointExceptionType::InvalidLatitude, (string) $latitude);
        }

        $this->latitude = $latitude;

        return $this;
    }

    public function setAltitude(float $altitude): static
    {
        if ($this->altitude !== null) {
            throw new PointException(PointExceptionType::AltitudeSet);
        }

        $this->altitude = $altitude;

        return $this;
    }

    public function getLongitude(): float
    {
        return $this->longitude ?? throw new PointException(PointExceptionType::PointNotSet, 'longitude');
    }

    public function getLatitude(): float
    {
        return $this->latitude ?? throw new PointException(PointExceptionType::PointNotSet, 'latitude');
    }

    public function getAltitude(): ?float
    {
        return $this->altitude;
    }

    public function equals(self $point): bool
    {
        return $this->toArray() === $point->toArray();
    }

    /**
     * @return array<int, float>
     */
    public function toArray(): array
    {
        $coordinates = [$this->getLongitude(), $this->getLatitude()];

        if ($this->altitude !== null) {
            $coordinates[] = $this->altitude;
        }

        return $coordinates;
    }
}
