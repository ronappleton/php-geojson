<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Tests\Unit;

use JsonException;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\Factory;
use RonAppleton\GeoJson\Objects\Point;

class PointTest extends \PHPUnit\Framework\TestCase
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
}
