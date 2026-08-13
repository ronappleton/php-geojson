<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use RonAppleton\GeoJson\Abstracts\GeoJsonObject;
use RonAppleton\GeoJson\Enums\PolygonExceptionType;
use RonAppleton\GeoJson\Exceptions\NotEnoughPoints;
use RonAppleton\GeoJson\Exceptions\Polygon as PolygonException;

use function array_map;
use function count;

class Polygon extends GeoJsonObject
{
    /**
     * @var array<int, array<int, Point>>
     */
    private array $rings = [];

    public function setExteriorRing(Point ... $points): static
    {
        if ($this->rings !== []) {
            throw new PolygonException(PolygonExceptionType::ExteriorRingSet);
        }

        $this->rings[] = $points;

        return $this;
    }

    public function addInteriorRing(Point ... $points): static
    {
        if ($this->rings === []) {
            throw new PolygonException(PolygonExceptionType::ExteriorRingNotSet);
        }

        $this->rings[] = $points;

        return $this;
    }

    /**
     * @return array<int, array<int, Point>>
     */
    public function getRings(): array
    {
        return $this->rings;
    }

    /**
     * @return array<int, array<int, array<int, float>>>
     */
    public function coordinates(): array
    {
        $coordinates = [];

        foreach ($this->rings as $ring) {
            $coordinates[] = $this->ringCoordinates($ring);
        }

        return $coordinates;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->geometryArray($this->coordinates());
    }

    /**
     * @param array<int, Point> $ring
     *
     * @return array<int, array<int, float>>
     */
    private function ringCoordinates(array $ring): array
    {
        $coordinates = array_map(static fn (Point $point) => $point->toArray(), $ring);

        if (count($coordinates) < 4) {
            throw new NotEnoughPoints(count($coordinates), 4);
        }

        if ($coordinates[0] !== $coordinates[count($coordinates) - 1]) {
            throw new PolygonException(PolygonExceptionType::RingNotClosed);
        }

        return $coordinates;
    }
}
