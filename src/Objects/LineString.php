<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use RonAppleton\GeoJson\Abstracts\GeoJsonObject;
use RonAppleton\GeoJson\Exceptions\NotEnoughPoints;

use function array_map;
use function array_merge;
use function count;

class LineString extends GeoJsonObject
{
    /**
     * @var array<int, Point>
     */
    private array $points = [];

    /**
     * @return array<int, Point>
     */
    public function getPoints(): array
    {
        return $this->points;
    }

    public function addPoint(Point $point): static
    {
        $this->points[] = $point;

        return $this;
    }

    public function addPoints(Point ... $points): static
    {
        $this->points = array_merge($this->points, $points);

        return $this;
    }

    /**
     * @return array<int, array<int, float>>
     */
    public function coordinates(): array
    {
        return array_map(static fn (Point $point) => $point->toArray(), $this->points);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        if (count($this->points) < 2) {
            throw new NotEnoughPoints(count($this->points), 2);
        }

        return $this->geometryArray($this->coordinates());
    }
}
