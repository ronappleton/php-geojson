<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Tests\Unit;

use JsonException;
use PHPUnit\Framework\TestCase;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Exceptions\BoundingBox as BoundingBoxException;
use RonAppleton\GeoJson\Objects\BoundingBox;
use RonAppleton\GeoJson\Objects\Factory;
use RonAppleton\GeoJson\Objects\Point;

class BoundingBoxTest extends TestCase
{
    private Point $southwest;

    private Point $northeast;

    public function testSetPoints(): void
    {
        $boundingBox = Factory::make(GeoJsonType::BoundingBox);
        $boundingBox->setPoints($this->southwest, $this->northeast);

        $this->assertInstanceOf(BoundingBox::class, $boundingBox);
        $this->assertCount(2, $boundingBox->getPoints());
    }

    public function testCannotSetPointsTwice(): void
    {
        $boundingBox = Factory::make(GeoJsonType::BoundingBox);
        $boundingBox->setPoints($this->southwest, $this->northeast);

        $this->expectException(BoundingBoxException::class);
        $this->expectExceptionMessage('The bounding box points are already set.');

        $boundingBox->setPoints($this->southwest, $this->northeast);
    }

    public function testInvalidOrder(): void
    {
        $boundingBox = Factory::make(GeoJsonType::BoundingBox);

        $this->expectException(BoundingBoxException::class);
        $this->expectExceptionMessage('The southwest corner must not be greater than the northeast corner.');

        $boundingBox->setPoints($this->northeast, $this->southwest);
    }

    public function testSetAltitudes(): void
    {
        $boundingBox = Factory::make(GeoJsonType::BoundingBox);
        $boundingBox->setAltitudes(- 4.0, 6.0);

        $this->assertSame(
            ['maximum_altitude' => 6.0, 'minimum_altitude' => - 4.0],
            $boundingBox->getAltitudes(),
        );
    }

    public function testInvalidAltitudeOrder(): void
    {
        $boundingBox = Factory::make(GeoJsonType::BoundingBox);

        $this->expectException(BoundingBoxException::class);
        $this->expectExceptionMessage('The minimum altitude must not be greater than the maximum altitude.');

        $boundingBox->setAltitudes(6.0, - 4.0);
    }

    public function testSouthwestLongitudeEqualToNortheastRejected(): void
    {
        $northeast = Factory::make(GeoJsonType::Point);
        $northeast->setPoints(100.0, 1.0);

        $boundingBox = Factory::make(GeoJsonType::BoundingBox);

        $this->expectException(BoundingBoxException::class);
        $this->expectExceptionMessage('The southwest corner must not be greater than the northeast corner.');

        $boundingBox->setPoints($this->southwest, $northeast);
    }

    public function testSouthwestLatitudeEqualToNortheastRejected(): void
    {
        $northeast = Factory::make(GeoJsonType::Point);
        $northeast->setPoints(101.0, 0.0);

        $boundingBox = Factory::make(GeoJsonType::BoundingBox);

        $this->expectException(BoundingBoxException::class);
        $this->expectExceptionMessage('The southwest corner must not be greater than the northeast corner.');

        $boundingBox->setPoints($this->southwest, $northeast);
    }

    public function testJustInsideBoundsAccepted(): void
    {
        $northeast = Factory::make(GeoJsonType::Point);
        $northeast->setPoints(100.0001, 0.0001);

        $boundingBox = Factory::make(GeoJsonType::BoundingBox);
        $boundingBox->setPoints($this->southwest, $northeast);

        $this->assertSame([100.0, 0.0, 100.0001, 0.0001], $boundingBox->toArray());
    }

    public function testEqualAltitudesRejected(): void
    {
        $boundingBox = Factory::make(GeoJsonType::BoundingBox);

        $this->expectException(BoundingBoxException::class);
        $this->expectExceptionMessage('The minimum altitude must not be greater than the maximum altitude.');

        $boundingBox->setAltitudes(6.0, 6.0);
    }

    public function testJustInsideAltitudesAccepted(): void
    {
        $boundingBox = Factory::make(GeoJsonType::BoundingBox);
        $boundingBox->setAltitudes(5.9999, 6.0);

        $this->assertSame(
            ['maximum_altitude' => 6.0, 'minimum_altitude' => 5.9999],
            $boundingBox->getAltitudes(),
        );
    }

    public function testCannotGetPointsWhenNotSet(): void
    {
        $boundingBox = Factory::make(GeoJsonType::BoundingBox);

        $this->expectException(BoundingBoxException::class);
        $this->expectExceptionMessage('The bounding box points are not set.');

        $boundingBox->getPoints();
    }

    public function testCannotGetAltitudesWhenNotSet(): void
    {
        $boundingBox = Factory::make(GeoJsonType::BoundingBox);

        $this->expectException(BoundingBoxException::class);
        $this->expectExceptionMessage('The bounding box altitudes are not set.');

        $boundingBox->getAltitudes();
    }

    public function testCannotSetAltitudesTwice(): void
    {
        $boundingBox = Factory::make(GeoJsonType::BoundingBox);
        $boundingBox->setAltitudes(- 4.0, 6.0);

        $this->expectException(BoundingBoxException::class);
        $this->expectExceptionMessage('The bounding box altitudes are already set.');

        $boundingBox->setAltitudes(- 2.0, 8.0);
    }

    /**
     * @throws JsonException
     */
    public function testToJson(): void
    {
        $boundingBox = Factory::make(GeoJsonType::BoundingBox);
        $boundingBox->setPoints($this->southwest, $this->northeast);

        $this->assertJsonStringEqualsJsonString('[100.0,0.0,101.0,1.0]', $boundingBox->toJson());
    }

    public function testToArray(): void
    {
        $boundingBox = Factory::make(GeoJsonType::BoundingBox);
        $boundingBox->setPoints($this->southwest, $this->northeast);

        $this->assertSame([100.0, 0.0, 101.0, 1.0], $boundingBox->toArray());
    }

    public function testToArrayWithAltitudes(): void
    {
        $boundingBox = Factory::make(GeoJsonType::BoundingBox);
        $boundingBox->setPoints($this->southwest, $this->northeast);
        $boundingBox->setAltitudes(- 4.0, 6.0);

        $this->assertSame([100.0, 0.0, - 4.0, 101.0, 1.0, 6.0], $boundingBox->toArray());
    }

    public function testCannotToArrayWithoutPoints(): void
    {
        $boundingBox = Factory::make(GeoJsonType::BoundingBox);

        $this->expectException(BoundingBoxException::class);
        $this->expectExceptionMessage('The bounding box points are not set.');

        $boundingBox->toArray();
    }

    protected function setUp(): void
    {
        $this->southwest = Factory::make(GeoJsonType::Point);
        $this->southwest->setPoints(100.0, 0.0);

        $this->northeast = Factory::make(GeoJsonType::Point);
        $this->northeast->setPoints(101.0, 1.0);
    }
}
