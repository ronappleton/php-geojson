<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Exceptions\GeometryCollection as GeometryCollectionException;
use RonAppleton\GeoJson\Objects\Factory;
use RonAppleton\GeoJson\Objects\GeometryCollection;
use RonAppleton\GeoJson\Objects\Point;

class GeometryCollectionTest extends TestCase
{
    public function testAddGeometry(): void
    {
        $point = Factory::make(GeoJsonType::Point);
        $point->setPoints(100.0, 0.0);

        $geometryCollection = Factory::make(GeoJsonType::GeometryCollection);
        $geometryCollection->addGeometry($point);

        $this->assertInstanceOf(GeometryCollection::class, $geometryCollection);
        $this->assertCount(1, $geometryCollection->getGeometries());
    }

    public function testCannotAddFeature(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);

        $geometryCollection = Factory::make(GeoJsonType::GeometryCollection);

        $this->expectException(GeometryCollectionException::class);
        $this->expectExceptionMessage('Only geometry objects may be added to a GeometryCollection.');

        $geometryCollection->addGeometry($feature);
    }

    public function testToArray(): void
    {
        $point = Factory::make(GeoJsonType::Point);
        $point->setPoints(100.0, 0.0);

        $geometryCollection = Factory::make(GeoJsonType::GeometryCollection);
        $geometryCollection->addGeometry($point);

        $this->assertSame(
            [
                'type' => 'GeometryCollection',
                'geometries' => [
                    [100.0, 0.0],
                ],
            ],
            $geometryCollection->toArray(),
        );
    }
}
