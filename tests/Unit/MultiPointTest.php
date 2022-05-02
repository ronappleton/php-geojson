<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Tests\Unit;

use JsonException;
use PHPUnit\Framework\TestCase;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\Factory;
use RonAppleton\GeoJson\Objects\MultiPoint;
use RonAppleton\GeoJson\Objects\Point;

class MultiPointTest extends TestCase
{
    public function testAddAndGetPoint(): void
    {
        $point = Factory::make(GeoJsonType::Point);
        
        $this->assertInstanceOf(Point::class, $point);
        
        $point->setPoints(123.456, 456.789);
        
        $multiPoint = Factory::make(GeoJsonType::MultiPoint);
        
        $this->assertInstanceOf(MultiPoint::class, $multiPoint);
        
        $multiPoint->addPoint($point);
        $getPoints = $multiPoint->getPoints();
        
        $this->assertIsArray($getPoints);
        $this->assertArrayHasKey(0, $getPoints);
        $this->assertInstanceOf(Point::class, $getPoints[0]);
    }
    
    public function testGetPoints(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $this->assertInstanceOf(Point::class, $point);

        $point->setPoints(123.456, 456.789);

        $multiPoint = Factory::make(GeoJsonType::MultiPoint);

        $this->assertInstanceOf(MultiPoint::class, $multiPoint);

        $multiPoint->addPoint($point);
        $getPoints = $multiPoint->getPoints();

        $this->assertIsArray($getPoints);
        $this->assertArrayHasKey(0, $getPoints);
        $this->assertInstanceOf(Point::class, $getPoints[0]);
    }

    public function testToArray(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $this->assertInstanceOf(Point::class, $point);

        $point->setPoints(123.456, 456.789);

        $multiPoint = Factory::make(GeoJsonType::MultiPoint);

        $this->assertInstanceOf(MultiPoint::class, $multiPoint);

        $multiPoint->addPoint($point);
        
        $toArray = $multiPoint->toArray();
        
        $this->assertIsArray($toArray);
        $this->assertSame([[123.456, 456.789]], $toArray);
    }

    /**
     * @throws JsonException
     */
    public function testToJson(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $this->assertInstanceOf(Point::class, $point);

        $point->setPoints(123.456, 456.789);

        $multiPoint = Factory::make(GeoJsonType::MultiPoint);

        $this->assertInstanceOf(MultiPoint::class, $multiPoint);

        $multiPoint->addPoint($point);
        
        $toJson = $multiPoint->toJson();
        
        $this->assertJson($toJson);
        $this->assertSame('[[123.456,456.789]]', $toJson);
    }
}
