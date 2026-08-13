<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Tests\Unit;

use JsonException;
use PHPUnit\Framework\TestCase;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Exceptions\NotEnoughPoints;
use RonAppleton\GeoJson\Objects\Factory;
use RonAppleton\GeoJson\Objects\LineString;
use RonAppleton\GeoJson\Objects\Point;

class LineStringTest extends TestCase
{
    public function testAddPoint(): void
    {
        $point = Factory::make(GeoJsonType::Point);
        $point->setPoints(123.456, 45.678);

        $linestring = Factory::make(GeoJsonType::LineString);
        $linestring->addPoint($point);

        $this->assertInstanceOf(LineString::class, $linestring);
        $this->assertCount(1, $linestring->getPoints());
    }

    public function testAddPoints(): void
    {
        [$point, $point2] = Factory::make(GeoJsonType::Point, 2);

        $point->setPoints(123.456, 45.678);
        $point2->setPoints(- 45.678, - 12.345);

        $linestring = Factory::make(GeoJsonType::LineString);
        $linestring->addPoints($point, $point2);

        $this->assertCount(2, $linestring->getPoints());
    }

    public function testGetPoints(): void
    {
        [$point, $point2] = Factory::make(GeoJsonType::Point, 2);

        $point->setPoints(123.456, 45.678);
        $point2->setPoints(- 45.678, - 12.345);

        $linestring = Factory::make(GeoJsonType::LineString);
        $linestring->addPoints($point, $point2);

        $points = $linestring->getPoints();

        $this->assertSame(123.456, $points[0]->getLongitude());
        $this->assertSame(45.678, $points[0]->getLatitude());
        $this->assertSame(- 45.678, $points[1]->getLongitude());
        $this->assertSame(- 12.345, $points[1]->getLatitude());
    }

    public function testCoordinates(): void
    {
        [$point, $point2] = Factory::make(GeoJsonType::Point, 2);

        $point->setPoints(123.456, 45.678);
        $point2->setPoints(- 45.678, - 12.345);

        $linestring = Factory::make(GeoJsonType::LineString);
        $linestring->addPoints($point, $point2);

        $this->assertSame(
            [[123.456, 45.678], [- 45.678, - 12.345]],
            $linestring->coordinates(),
        );
    }

    public function testToArray(): void
    {
        [$point, $point2] = Factory::make(GeoJsonType::Point, 2);

        $point->setPoints(123.456, 45.678);
        $point2->setPoints(- 45.678, - 12.345);

        $linestring = Factory::make(GeoJsonType::LineString);
        $linestring->addPoints($point, $point2);

        $this->assertSame(
            [
                'type' => 'LineString',
                'coordinates' => [[123.456, 45.678], [- 45.678, - 12.345]],
            ],
            $linestring->toArray(),
        );
    }

    /**
     * @throws JsonException
     */
    public function testToJson(): void
    {
        [$point, $point2] = Factory::make(GeoJsonType::Point, 2);

        $point->setPoints(123.456, 45.678);
        $point2->setPoints(- 45.678, - 12.345);

        $linestring = Factory::make(GeoJsonType::LineString);
        $linestring->addPoints($point, $point2);

        $this->assertSame(
            '{"type":"LineString","coordinates":[[123.456,45.678],[-45.678,-12.345]]}',
            $linestring->toJson(),
        );
    }

    public function testNotEnoughPoints(): void
    {
        $point = Factory::make(GeoJsonType::Point);
        $point->setPoints(123.456, 45.678);

        $linestring = Factory::make(GeoJsonType::LineString);
        $linestring->addPoint($point);

        $this->expectException(NotEnoughPoints::class);
        $this->expectExceptionMessage('You have not provided enough points, 1 provided, 2 required.');

        $linestring->toArray();
    }
}
