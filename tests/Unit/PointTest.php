<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Tests\Unit;

use JsonException;
use PHPUnit\Framework\TestCase;
use RonAppleton\GeoJson\Abstracts\GeoJsonObject;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\Factory;
use RonAppleton\GeoJson\Objects\Point;

class PointTest extends TestCase
{
    public function testSetLongitude(): void
    {
        $point = Factory::make(GeoJsonType::Point);
        
        $this->assertInstanceOf(Point::class, $point);
        
        $point->setLongitude(123.456);
        
        $this->assertSame(123.456, $point->getLongitude());
    }
    
    public function testSetLatitude(): void
    {
        $point = Factory::make(GeoJsonType::Point);
        
        $this->assertInstanceOf(Point::class, $point);
        
        $point->setLatitude(456.789);
        
        $this->assertSame(456.789, $point->getLatitude());
    }

    public function testSetPoints(): void
    {
        $point = Factory::make(GeoJsonType::Point);

        $this->assertInstanceOf(Point::class, $point);
        
        $point->setPoints(123.456, 456.789);

        $this->assertSame(123.456, $point->getLongitude());
        $this->assertSame(456.789, $point->getLatitude());
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
        
        $$point->getType();
    }
}
