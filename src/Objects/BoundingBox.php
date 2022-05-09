<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use RonAppleton\GeoJson\Abstracts\GeoJsonObject;
use RonAppleton\GeoJson\Enums\BoundingBoxExceptionType;
use RonAppleton\GeoJson\Exceptions\BoundingBox as BoundingBoxException;

use function array_map;
use function array_merge;

/**
 * @phpcs:disable SlevomatCodingStandard.Commenting.RequireOneLinePropertyDocComment.MultiLinePropertyComment
 */
class BoundingBox extends GeoJsonObject
{
    /**
     * @var array<int, float>
     */
    private array $points;
    
    private float $minimumAltitude;
    
    private float $maximumAltitude;

    /**
     * @return array<int, float>
     */
    public function getPoints(): array
    {
        return $this->points ?? throw new BoundingBoxException(BoundingBoxExceptionType::PointsNotSet);
    }
    
    public function setPoints(Point $southwest, Point $northeast,): BoundingBox 
    {
        if (isset($this->points)) {
            throw new BoundingBoxException(BoundingBoxExceptionType::PointsSet);
        }
        
        $this->points = [$southwest, $northeast];
        
        return $this;
    }

    /**
     * @return array<string, float>
     */
    public function getAltitudes(): array
    {
        return [
            'minimum_altitude' => $this->minimumAltitude ?? throw new BoundingBoxException(BoundingBoxExceptionType::AltitudesNotSet),
            'maximum_altitude' => $this->maximumAltitude ?? throw new BoundingBoxException(BoundingBoxExceptionType::AltitudesNotSet),
        ];
    }
    
    public function setAltitudes(float $minimumAltitude, float $maximumAltitude): BoundingBox
    {
        if (isset($this->minimumAltitude, $this->maximumAltitude)) {
            throw new BoundingBoxException(BoundingBoxExceptionType::AltitudesSet);
        }
        
        $this->minimumAltitude = $minimumAltitude;
        $this->maximumAltitude = $maximumAltitude;
        
        return $this;
    }

    /**
     * @return array<int, mixed>
     */
    public function toArray(): array
    {
        if (!isset($this->points)) {
            throw new BoundingBoxException(BoundingBoxExceptionType::PointsNotSet);
        }
        
        $pointsMap = array_map(static fn (Point $point) => $point->toArray(), $this->points);
        
        return $this->spliceInAltitudes(array_merge(... $pointsMap));
    }

    /**
     * @param array<int, float> $points
     * @return array<int, float>
     */
    private function spliceInAltitudes(array $points): array
    {
        if (isset($this->minimumAltitude, $this->maximumAltitude)) {
            array_splice($points, 2, 0, $this->minimumAltitude);
            $points[] = $this->maximumAltitude;
        }
        
        return $points;
    }
}
