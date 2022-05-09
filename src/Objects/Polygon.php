<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use RonAppleton\GeoJson\Abstracts\GeoJsonObject;

use RonAppleton\GeoJson\Enums\PolygonExceptionType;
use RonAppleton\GeoJson\Exceptions\Polygon as PolygonException;

use function array_merge;
use function array_map;

/**
 * @phpcs:disable SlevomatCodingStandard.Commenting.RequireOneLinePropertyDocComment.MultiLinePropertyComment
 */
class Polygon extends GeoJsonObject
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
        return $this->points ?? throw new PolygonException(PolygonExceptionType::PointsNotSet);
    }

    /**
     * @param array<int, Point> $points
     */
    public function setPoints(Point ... $points): Polygon
    {
        if (isset($this->points)) {
            throw new PolygonException(PolygonExceptionType::PointsSet);
        }
        
        $this->points = array_merge($this->points ?? [], $points);
        
        return $this;
    }

    /**
     * @return array<int, mixed>
     */
    public function toArray(): array
    {
        return array_map(static fn (Point $point) => $point->toArray(), $this->points);
    }
}
