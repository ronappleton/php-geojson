<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use RonAppleton\GeoJson\Abstracts\GeoJsonObject;
use RonAppleton\GeoJson\Enums\BoundingBoxExceptionType;
use RonAppleton\GeoJson\Exceptions\BoundingBox as BoundingBoxException;

use function array_splice;

class BoundingBox extends GeoJsonObject
{
    private ?Point $southwest = null;

    private ?Point $northeast = null;

    private ?float $minimumAltitude = null;

    private ?float $maximumAltitude = null;

    public function setPoints(Point $southwest, Point $northeast): static
    {
        if ($this->southwest !== null) {
            throw new BoundingBoxException(BoundingBoxExceptionType::PointsSet);
        }

        if (
            $southwest->getLongitude() >= $northeast->getLongitude()
            || $southwest->getLatitude() >= $northeast->getLatitude()
        ) {
            throw new BoundingBoxException(BoundingBoxExceptionType::InvalidOrder);
        }

        $this->southwest = $southwest;
        $this->northeast = $northeast;

        return $this;
    }

    /**
     * @return array<int, Point>
     */
    public function getPoints(): array
    {
        return [
            $this->southwest ?? throw new BoundingBoxException(BoundingBoxExceptionType::PointsNotSet),
            $this->northeast ?? throw new BoundingBoxException(BoundingBoxExceptionType::PointsNotSet),
        ];
    }

    public function setAltitudes(float $minimumAltitude, float $maximumAltitude): static
    {
        if ($this->minimumAltitude !== null) {
            throw new BoundingBoxException(BoundingBoxExceptionType::AltitudesSet);
        }

        if ($minimumAltitude >= $maximumAltitude) {
            throw new BoundingBoxException(BoundingBoxExceptionType::InvalidAltitudeOrder);
        }

        $this->minimumAltitude = $minimumAltitude;
        $this->maximumAltitude = $maximumAltitude;

        return $this;
    }

    /**
     * @return array{minimum_altitude: float, maximum_altitude: float}
     */
    public function getAltitudes(): array
    {
        return [
            'maximum_altitude' => $this->maximumAltitude ?? throw new BoundingBoxException(
                BoundingBoxExceptionType::AltitudesNotSet,
            ),
            'minimum_altitude' => $this->minimumAltitude ?? throw new BoundingBoxException(
                BoundingBoxExceptionType::AltitudesNotSet,
            ),
        ];
    }

    /**
     * @return array<int, float>
     */
    public function toArray(): array
    {
        $southwest = $this->southwest ?? throw new BoundingBoxException(BoundingBoxExceptionType::PointsNotSet);
        $northeast = $this->northeast ?? throw new BoundingBoxException(BoundingBoxExceptionType::PointsNotSet);

        $bbox = [
            $southwest->getLongitude(),
            $southwest->getLatitude(),
            $northeast->getLongitude(),
            $northeast->getLatitude(),
        ];

        if ($this->minimumAltitude !== null && $this->maximumAltitude !== null) {
            array_splice($bbox, 2, 0, $this->minimumAltitude);
            $bbox[] = $this->maximumAltitude;
        }

        return $bbox;
    }
}
