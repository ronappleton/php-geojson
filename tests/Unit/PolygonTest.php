<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Exceptions\NotEnoughPoints;
use RonAppleton\GeoJson\Exceptions\Polygon as PolygonException;
use RonAppleton\GeoJson\Objects\Factory;
use RonAppleton\GeoJson\Objects\Point;
use RonAppleton\GeoJson\Objects\Polygon;

class PolygonTest extends TestCase
{
    private Point $point;

    private Point $point2;

    private Point $point3;

    private Point $point4;

    public function testSetExteriorRing(): void
    {
        $polygon = Factory::make(GeoJsonType::Polygon);
        $polygon->setExteriorRing($this->point, $this->point2, $this->point3, $this->point4);

        $this->assertInstanceOf(Polygon::class, $polygon);
        $this->assertCount(1, $polygon->getRings());
    }

    public function testCannotSetExteriorRingTwice(): void
    {
        $polygon = Factory::make(GeoJsonType::Polygon);
        $polygon->setExteriorRing($this->point, $this->point2, $this->point3, $this->point4);

        $this->expectException(PolygonException::class);
        $this->expectExceptionMessage('The exterior ring is already set.');

        $polygon->setExteriorRing($this->point, $this->point2, $this->point3, $this->point4);
    }

    public function testCannotAddInteriorRingBeforeExterior(): void
    {
        $polygon = Factory::make(GeoJsonType::Polygon);

        $this->expectException(PolygonException::class);
        $this->expectExceptionMessage('The exterior ring must be set before adding interior rings.');

        $polygon->addInteriorRing($this->point, $this->point2, $this->point3, $this->point4);
    }

    public function testAddInteriorRing(): void
    {
        $polygon = Factory::make(GeoJsonType::Polygon);
        $polygon->setExteriorRing($this->point, $this->point2, $this->point3, $this->point4);
        $polygon->addInteriorRing($this->point, $this->point2, $this->point3, $this->point4);

        $this->assertCount(2, $polygon->getRings());
    }

    public function testToArrayWithHole(): void
    {
        [$holePoint, $holePoint2, $holePoint3, $holePoint4] = Factory::make(GeoJsonType::Point, 4);

        $holePoint->setPoints(100.2, 0.2);
        $holePoint2->setPoints(100.8, 0.2);
        $holePoint3->setPoints(100.8, 0.8);
        $holePoint4->setPoints(100.2, 0.2);

        $polygon = Factory::make(GeoJsonType::Polygon);
        $polygon->setExteriorRing($this->point, $this->point2, $this->point3, $this->point4);
        $polygon->addInteriorRing($holePoint, $holePoint2, $holePoint3, $holePoint4);

        $this->assertSame(
            [
                'type' => 'Polygon',
                'coordinates' => [
                    [[100.0, 0.0], [101.0, 0.0], [101.0, 1.0], [100.0, 0.0]],
                    [[100.2, 0.2], [100.8, 0.2], [100.8, 0.8], [100.2, 0.2]],
                ],
            ],
            $polygon->toArray(),
        );
    }

    public function testRingNotClosed(): void
    {
        $point5 = Factory::make(GeoJsonType::Point);
        $point5->setPoints(100.0, 1.0);

        $polygon = Factory::make(GeoJsonType::Polygon);
        $polygon->setExteriorRing($this->point, $this->point2, $this->point3, $point5);

        $this->expectException(PolygonException::class);
        $this->expectExceptionMessage('Polygon rings must be closed. The first and last positions must be identical.');

        $polygon->toArray();
    }

    public function testNotEnoughPoints(): void
    {
        $polygon = Factory::make(GeoJsonType::Polygon);
        $polygon->setExteriorRing($this->point, $this->point2, $this->point3);

        $this->expectException(NotEnoughPoints::class);
        $this->expectExceptionMessage('You have not provided enough points, 3 provided, 4 required.');

        $polygon->toArray();
    }

    protected function setUp(): void
    {
        $this->point = Factory::make(GeoJsonType::Point);
        $this->point->setPoints(100.0, 0.0);

        $this->point2 = Factory::make(GeoJsonType::Point);
        $this->point2->setPoints(101.0, 0.0);

        $this->point3 = Factory::make(GeoJsonType::Point);
        $this->point3->setPoints(101.0, 1.0);

        $this->point4 = Factory::make(GeoJsonType::Point);
        $this->point4->setPoints(100.0, 0.0);
    }
}
