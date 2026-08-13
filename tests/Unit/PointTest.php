<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Tests\Unit;

use JsonException;
use PHPUnit\Framework\TestCase;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Exceptions\Point as PointException;
use RonAppleton\GeoJson\Objects\Factory;
use RonAppleton\GeoJson\Objects\Point;

class PointTest extends TestCase
{
    public function testSetPoints(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $this->assertInstanceOf(Point::class, $point);

        $point->setPoints(- 80.5, 31.54);

        $this->assertSame(- 80.5, $point->getLongitude());
        $this->assertSame(31.54, $point->getLatitude());
    }

    public function testCannotSetPointsTwice(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $point->setPoints(- 80.5, 31.54);

        $this->expectException(PointException::class);
        $this->expectExceptionMessage('The longitude coordinate is already set.');

        $point->setPoints(90.5, 3.54);
    }

    public function testSetLongitudeAndLatitude(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $point->setLongitude(100.5);
        $point->setLatitude(- 45.2);

        $this->assertSame(100.5, $point->getLongitude());
        $this->assertSame(- 45.2, $point->getLatitude());
    }

    public function testInvalidLongitude(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $this->expectException(PointException::class);
        $this->expectExceptionMessage('Longitude must be between -180 and 180, 200 given.');

        $point->setLongitude(200.0);
    }

    public function testInvalidLatitude(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $this->expectException(PointException::class);
        $this->expectExceptionMessage('Latitude must be between -90 and 90, -91 given.');

        $point->setLatitude(- 91.0);
    }

    public function testLongitudeAtMinimumBoundary(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $point->setLongitude(- 180.0);

        $this->assertSame(- 180.0, $point->getLongitude());
    }

    public function testLongitudeAtMaximumBoundary(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $point->setLongitude(180.0);

        $this->assertSame(180.0, $point->getLongitude());
    }

    public function testLongitudeBelowMinimumRejected(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $this->expectException(PointException::class);
        $this->expectExceptionMessage('Longitude must be between -180 and 180, -180.0001 given.');

        $point->setLongitude(- 180.0001);
    }

    public function testLongitudeAboveMaximumRejected(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $this->expectException(PointException::class);
        $this->expectExceptionMessage('Longitude must be between -180 and 180, 180.0001 given.');

        $point->setLongitude(180.0001);
    }

    public function testLatitudeAtMinimumBoundary(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $point->setLatitude(- 90.0);

        $this->assertSame(- 90.0, $point->getLatitude());
    }

    public function testLatitudeAtMaximumBoundary(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $point->setLatitude(90.0);

        $this->assertSame(90.0, $point->getLatitude());
    }

    public function testLatitudeBelowMinimumRejected(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $this->expectException(PointException::class);
        $this->expectExceptionMessage('Latitude must be between -90 and 90, -90.0001 given.');

        $point->setLatitude(- 90.0001);
    }

    public function testLatitudeAboveMaximumRejected(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $this->expectException(PointException::class);
        $this->expectExceptionMessage('Latitude must be between -90 and 90, 90.0001 given.');

        $point->setLatitude(90.0001);
    }

    public function testCannotGetLongitudeWhenNotSet(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $this->expectException(PointException::class);
        $this->expectExceptionMessage('The longitude coordinate is not set.');

        $point->getLongitude();
    }

    public function testCannotGetLatitudeWhenNotSet(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $this->expectException(PointException::class);
        $this->expectExceptionMessage('The latitude coordinate is not set.');

        $point->getLatitude();
    }

    public function testSetAltitude(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $point->setPoints(100.5, - 45.2);
        $point->setAltitude(1000.0);

        $this->assertSame(1000.0, $point->getAltitude());
    }

    public function testCannotSetAltitudeTwice(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $point->setPoints(100.5, - 45.2);
        $point->setAltitude(1000.0);

        $this->expectException(PointException::class);
        $this->expectExceptionMessage('The altitude is already set.');

        $point->setAltitude(2000.0);
    }

    public function testGetAltitudeWhenNotSet(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $this->assertNull($point->getAltitude());
    }

    public function testToArray(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $point->setPoints(- 80.5, 31.54);

        $this->assertSame([- 80.5, 31.54], $point->toArray());
    }

    public function testToArrayWithAltitude(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $point->setPoints(- 80.5, 31.54);
        $point->setAltitude(100.0);

        $this->assertSame([- 80.5, 31.54, 100.0], $point->toArray());
    }

    /**
     * @throws JsonException
     */
    public function testToJson(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $point->setPoints(- 80.5, 31.54);

        $this->assertSame('[-80.5,31.54]', $point->toJson());
    }

    public function testEquals(): void
    {
        [$point, $point2] = Factory::make(GeoJsonType::Point, 2);

        $point->setPoints(- 80.5, 31.54);
        $point2->setPoints(- 80.5, 31.54);

        $this->assertTrue($point->equals($point2));
    }

    public function testNotEquals(): void
    {
        [$point, $point2] = Factory::make(GeoJsonType::Point, 2);

        $point->setPoints(- 80.5, 31.54);
        $point2->setPoints(- 80.6, 31.54);

        $this->assertFalse($point->equals($point2));
    }

    public function testNotEqualsWithDifferentAltitude(): void
    {
        [$point, $point2] = Factory::make(GeoJsonType::Point, 2);

        $point->setPoints(- 80.5, 31.54);
        $point2->setPoints(- 80.5, 31.54);
        $point2->setAltitude(100.0);

        $this->assertFalse($point->equals($point2));
    }
}
