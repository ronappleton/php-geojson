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

        $this->assertInstanceOf(Point::class, $point);
        
        $point->setPoints(123.456, 456.789);
        
        $linestring = Factory::make(GeoJsonType::LineString);
        
        $this->assertInstanceOf(LineString::class, $linestring);
        
        $linestring->addPoint($point);
        
        $points = $linestring->getPoints();
        
        $this->assertIsArray($points);
        $this->assertCount(1, $points);
    }
    
    public function testAddPoints(): void
    {
        [$point, $point2] = Factory::make(GeoJsonType::Point, 2);

        $this->assertInstanceOf(Point::class, $point);
        $this->assertInstanceOf(Point::class, $point2);
        
        $point->setPoints(123.456, 456.789);
        $point2->setPoints(789.012, 345.678);

        $linestring = Factory::make(GeoJsonType::LineString);

        $this->assertInstanceOf(LineString::class, $linestring);

        $linestring->addPoints($point, $point2);

        $points = $linestring->getPoints();

        $this->assertIsArray($points);
        $this->assertCount(2, $points);
    }

    public function testGetPoints(): void
    {
        [$point, $point2] = Factory::make(GeoJsonType::Point, 2);

        $this->assertInstanceOf(Point::class, $point);
        $this->assertInstanceOf(Point::class, $point2);
        
        $point->setPoints(123.456, 456.789);
        $point2->setPoints(789.012, 012.345);

        $linestring = Factory::make(GeoJsonType::LineString);

        $this->assertInstanceOf(LineString::class, $linestring);

        $linestring->addPoints($point, $point2);

        $points = $linestring->getPoints();

        $this->assertIsArray($points);
        $this->assertCount(2, $points);
        $this->assertInstanceOf(Point::class, $points[0]);
        $this->assertInstanceOf(Point::class, $points[1]);
        $this->assertSame(123.456, $points[0]->getLongitude());
        $this->assertSame(456.789, $points[0]->getLatitude());
        $this->assertSame(789.012, $points[1]->getLongitude());
        $this->assertSame(012.345, $points[1]->getLatitude());
    }
    
    public function testToArray(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $this->assertInstanceOf(Point::class, $point);

        $point->setPoints(123.456, 456.789);

        $point2 = Factory::make(GeoJsonType::Point);

        $this->assertInstanceOf(Point::class, $point2);

        $point2->setPoints(789.012, 345.678);

        $linestring = Factory::make(GeoJsonType::LineString);

        $this->assertInstanceOf(LineString::class, $linestring);

        $linestring->addPoints($point, $point2);
        
        $array = $linestring->toArray();
        
        $this->assertIsArray($array);
        $this->assertSame([[123.456, 456.789], [789.012, 345.678]], $array);
    }

    /**
     * @throws JsonException
     */
    public function testToJson(): void
    {
        [$point, $point2] = Factory::make(GeoJsonType::Point, 2);

        $this->assertInstanceOf(Point::class, $point);
        $this->assertInstanceOf(Point::class, $point2);

        $point->setPoints(123.456, 456.789);
        $point2->setPoints(789.012, 345.678);

        $linestring = Factory::make(GeoJsonType::LineString);

        $this->assertInstanceOf(LineString::class, $linestring);

        $linestring->addPoints($point, $point2);
        
        $json = $linestring->toJson();
        $this->assertSame('[[123.456,456.789],[789.012,345.678]]', $json);
    }

    public function testNotEnoughPoints(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $this->assertInstanceOf(Point::class, $point);

        $point->setPoints(123.456, 456.789);

        $linestring = Factory::make(GeoJsonType::LineString);

        $this->assertInstanceOf(LineString::class, $linestring);
        
        $linestring->addPoint($point);

        $this->expectException(NotEnoughPoints::class);
        $this->expectExceptionMessage("You have not provided enough points, 1 provided, 2 required.");
        $linestring->toArray();
    }
}
