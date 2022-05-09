<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Tests\Unit;

use JsonException;
use PHPUnit\Framework\TestCase;
use RonAppleton\GeoJson\Abstracts\GeoJsonObject;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\Factory;
use RonAppleton\GeoJson\Objects\Point;
use RonAppleton\GeoJson\Exceptions\Point as PointException;

class PointTest extends TestCase
{
    private Point $point;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->point = Factory::make(GeoJsonType::Point);
    }

    public function testSetLongitude(): void
    {
        $this->point->setLongitude(123.456);
        
        $this->assertSame(123.456, $this->point->getLongitude());
    }

    public function testCannotSetLongitude(): void
    {
        $this->point->setLongitude(123.456);
        
        $this->expectException(PointException::class);
        $this->expectExceptionMessage('The longitude point is already set.');

        $this->point->setLongitude(123.456);
    }
    
    public function testSetLatitude(): void
    {
        $this->point->setLatitude(456.789);
        
        $this->assertSame(456.789, $this->point->getLatitude());
    }
    
    public function testCannotSetLatitude(): void
    {
        $this->point->setLatitude(456.789);
        
        $this->expectException(PointException::class);
        $this->expectExceptionMessage('The latitude point is already set.');

        $this->point->setLatitude(456.789);
    }

    public function testSetPoints(): void
    {
        $this->point->setPoints(123.456, 456.789);

        $this->assertSame(123.456, $this->point->getLongitude());
        $this->assertSame(456.789, $this->point->getLatitude());
    }
    
    public function testCannotSetPointsLongitudeExists(): void
    {
        $this->point->setLongitude(100.0);
        
        $this->expectException(PointException::class);
        $this->expectExceptionMessage('The longitude point is already set.');
        
        $this->point->setPoints(100.0, 0.0);
    }
    
    public function testCannotSetPointsLatitudeExists(): void
    {
        $this->point->setLatitude(100.0);
        
        $this->expectException(PointException::class);
        $this->expectExceptionMessage('The latitude point is already set.');
        
        $this->point->setPoints(100.0, 0.0);
    }
    
    public function testToArray(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $this->assertInstanceOf(Point::class, $point);

        $point->setPoints(123.456, 456.789);
        
        $toArray = $point->toArray();
        
        $this->assertSame([123.456, 456.789], $toArray);
    }

    /**
     * @throws JsonException
     */
    public function testToJson(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $this->assertInstanceOf(Point::class, $point);

        $point->setPoints(123.456, 456.789);
        
        $toJson = $point->toJson();

        $this->assertSame('[123.456,456.789]', $toJson);
    }
    
    public function testLongitude(): void
    {
        $point = Factory::make(GeoJsonType::Point);
        
        $this->assertInstanceOf(Point::class, $point);
        
        $point->setLongitude(123.123);
        $longitude = $point->getLongitude();
        $this->assertSame(123.123, $longitude);
    }
    
    public function testLatitude(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $this->assertInstanceOf(Point::class, $point);

        $point->setLatitude(123.123);
        $longitude = $point->getLatitude();
        $this->assertSame(123.123, $longitude);
    }
    
    public function testGetType(): void
    {
        $point = Factory::make(GeoJsonType::Point);
        
        $this->assertInstanceOf(GeoJsonObject::class, $point);
        
        $point->getType();
    }
}
