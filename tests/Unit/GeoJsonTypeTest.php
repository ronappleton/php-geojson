<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RonAppleton\GeoJson\Enums\GeoJsonType;

class GeoJsonTypeTest extends TestCase
{
    public function testValues(): void
    {
        $this->assertSame(
            [
                'Point',
                'MultiPoint',
                'LineString',
                'MultiLineString',
                'Polygon',
                'MultiPolygon',
                'GeometryCollection',
                'Feature',
                'FeatureCollection',
                'bbox',
            ],
            GeoJsonType::values(),
        );
    }

    public function testIsGeometry(): void
    {
        foreach (GeoJsonType::geometries() as $type) {
            $this->assertTrue($type->isGeometry(), $type->value);
        }
    }

    public function testFeatureIsNotGeometry(): void
    {
        $this->assertFalse(GeoJsonType::Feature->isGeometry());
    }

    public function testFeatureCollectionIsNotGeometry(): void
    {
        $this->assertFalse(GeoJsonType::FeatureCollection->isGeometry());
    }

    public function testBoundingBoxIsNotGeometry(): void
    {
        $this->assertFalse(GeoJsonType::BoundingBox->isGeometry());
    }
}
