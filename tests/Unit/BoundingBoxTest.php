<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Tests\Unit;

use JsonException;
use PHPUnit\Framework\TestCase;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\BoundingBox;
use RonAppleton\GeoJson\Objects\Factory;
use RonAppleton\GeoJson\Objects\Point;

class BoundingBoxTest extends TestCase
{
    public function testAddPoints(): void
    {
        $point = Factory::make(GeoJsonType::Point);
        
        $this->assertInstanceOf(Point::class, $point);
        
        $point->setPoints(123.456, 456.789);
        
        $point2 = Factory::make(GeoJsonType::Point);
        
        $this->assertInstanceOf(Point::class, $point2);
        
        $point2->setPoints(789.012, 012.345);
        
        $boundingBox = Factory::make(GeoJsonType::BoundingBox);
        
        $this->assertInstanceOf(BoundingBox::class, $boundingBox);
        
        $boundingBox->setPoints($point, $point2);
    }
    
    public function testGetPoints(): void
    {
        $point = Factory::make(GeoJsonType::Point);
        
        $this->assertInstanceOf(Point::class, $point);
        
        $point->setPoints(123.456, 456.789);
        
        $point2 = Factory::make(GeoJsonType::Point);
        
        $this->assertInstanceOf(Point::class, $point2);
        
        $point2->setPoints(789.012, 012.345);
        
        $boundingBox = Factory::make(GeoJsonType::BoundingBox);
        
        $this->assertInstanceOf(BoundingBox::class, $boundingBox);
        
        $boundingBox->setPoints($point, $point2);
        
        $points = $boundingBox->getPoints();
        
        $this->assertIsArray($points);
        $this->assertInstanceOf(Point::class, $points[0]);
        $this->assertInstanceOf(Point::class, $points[1]);
    }

    public function testToArray(): void
    {
        [$point, $point2] = Factory::make(GeoJsonType::Point, 2);

        $this->assertInstanceOf(Point::class, $point);
        $this->assertInstanceOf(Point::class, $point2);
        
        $point->setPoints(123.456, 456.789);
        $point2->setPoints(789.012, 012.345);

        $boundingBox = Factory::make(GeoJsonType::BoundingBox);

        $this->assertInstanceOf(BoundingBox::class, $boundingBox);

        $boundingBox->setPoints($point, $point2);
        
        $array = $boundingBox->toArray();
        
        $this->assertIsArray($array);
        $this->assertCount(4, $array);
        $this->assertSame([123.456, 456.789, 789.012, 012.345], $array);
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
        $point2->setPoints(789.012, 012.345);

        $boundingBox = Factory::make(GeoJsonType::BoundingBox);

        $this->assertInstanceOf(BoundingBox::class, $boundingBox);

        $boundingBox->setPoints($point, $point2);
        
        $json = $boundingBox->toJson();
        
        $this->assertJson($json);
        $this->assertSame('[123.456,456.789,789.012,12.345]', $json);
    }
}
