<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\Factory;
use RonAppleton\GeoJson\Objects\MultiPolygon;
use RonAppleton\GeoJson\Objects\Point;
use RonAppleton\GeoJson\Objects\Polygon;

class MultiPolygonTest extends TestCase
{
    public function testSetPolygons(): void
    {
        $polygon = $this->makePolygon();

        $multiPolygon = Factory::make(GeoJsonType::MultiPolygon);
        $multiPolygon->setPolygons($polygon, $polygon);

        $this->assertInstanceOf(MultiPolygon::class, $multiPolygon);
        $this->assertCount(2, $multiPolygon->getPolygons());
    }

    public function testToArray(): void
    {
        $polygon = $this->makePolygon();

        $multiPolygon = Factory::make(GeoJsonType::MultiPolygon);
        $multiPolygon->setPolygons($polygon);

        $this->assertSame(
            [
                'type' => 'MultiPolygon',
                'coordinates' => [
                    [
                        [[100.0, 0.0], [101.0, 0.0], [101.0, 1.0], [100.0, 0.0]],
                    ],
                ],
            ],
            $multiPolygon->toArray(),
        );
    }

    private function makePolygon(): Polygon
    {
        [$point, $point2, $point3, $point4] = Factory::make(GeoJsonType::Point, 4);

        $point->setPoints(100.0, 0.0);
        $point2->setPoints(101.0, 0.0);
        $point3->setPoints(101.0, 1.0);
        $point4->setPoints(100.0, 0.0);

        $polygon = Factory::make(GeoJsonType::Polygon);
        $polygon->setExteriorRing($point, $point2, $point3, $point4);

        return $polygon;
    }
}
