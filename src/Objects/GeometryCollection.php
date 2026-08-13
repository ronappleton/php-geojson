<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use RonAppleton\GeoJson\Abstracts\GeoJsonObject;
use RonAppleton\GeoJson\Enums\GeometryCollectionExceptionType;
use RonAppleton\GeoJson\Exceptions\GeometryCollection as GeometryCollectionException;
use RonAppleton\GeoJson\Interfaces\GeoJsonObject as GeoJsonObjectInterface;

use function array_map;

class GeometryCollection extends GeoJsonObject
{
    /**
     * @var array<int, GeoJsonObjectInterface>
     */
    private array $geometries = [];

    /**
     * @return array<int, GeoJsonObjectInterface>
     */
    public function getGeometries(): array
    {
        return $this->geometries;
    }

    public function addGeometry(GeoJsonObjectInterface $geometry): static
    {
        if (!$geometry->getType()->isGeometry()) {
            throw new GeometryCollectionException(GeometryCollectionExceptionType::NotAGeometry);
        }

        $this->geometries[] = $geometry;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $array = [
            'type' => $this->getType()->value,
        ];

        $array['geometries'] = array_map(
            static fn (GeoJsonObjectInterface $geometry) => $geometry->toArray(),
            $this->geometries,
        );

        return $this->withBoundingBox($array);
    }
}
