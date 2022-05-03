<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use RonAppleton\GeoJson\Abstracts\GeoJsonObject;

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
        return $this->points;
    }

    /**
     * @param array<int, Point> $points
     */
    public function setPoints(Point ... $points): Polygon
    {
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
