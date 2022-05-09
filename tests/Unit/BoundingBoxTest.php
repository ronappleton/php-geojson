<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Tests\Unit;

use JsonException;
use PHPUnit\Framework\TestCase;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\BoundingBox;
use RonAppleton\GeoJson\Objects\Factory;
use RonAppleton\GeoJson\Objects\Point;
use RonAppleton\GeoJson\Exceptions\BoundingBox as BoundingBoxException;

class BoundingBoxTest extends TestCase
{
    private BoundingBox $boundingBox;
    private Point $point;
    private Point $point2;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->boundingBox = Factory::make(GeoJsonType::BoundingBox);
        [$this->point, $this->point2]= Factory::make(GeoJsonType::Point, 2);
        
    }

    public function testSetPoints(): void
    {
        $this->point->setPoints(123.456, 456.789);
        $this->point2->setPoints(789.012, 012.345);
        
        $this->boundingBox->setPoints($this->point, $this->point2);
        
        $points = $this->boundingBox->getPoints();
        
        $this->assertIsArray($points);
        $this->assertInstanceOf(Point::class, $points[0]);
        $this->assertInstanceOf(Point::class, $points[1]);
    }
    
    public function testCannotSetPoints(): void
    {
        $this->point->setPoints(123.456, 456.789);
        $this->point2->setPoints(789.012, 012.345);
        
        $this->boundingBox->setPoints($this->point, $this->point2);

        $this->expectException(BoundingBoxException::class);
        $this->expectExceptionMessage('Points are already set.');
        
        $this->boundingBox->setPoints($this->point, $this->point2);
    }
    
    public function testGetPoints(): void
    {
        $this->point->setPoints(123.456, 456.789);
        $this->point2->setPoints(789.012, 012.345);
        
        $this->boundingBox->setPoints($this->point, $this->point2);
        
        $points = $this->boundingBox->getPoints();
        
        $this->assertIsArray($points);
        $this->assertInstanceOf(Point::class, $points[0]);
        $this->assertInstanceOf(Point::class, $points[1]);
    }
    
    public function testCannotGetPoints(): void
    {
        $this->expectException(BoundingBoxException::class);
        $this->expectExceptionMessage('Points are not set.');
        
        $this->boundingBox->getPoints();
    }
    
    public function testSetAltitudes(): void
    {
        $this->boundingBox->setAltitudes(100.0, 0.0);
        
        $array = $this->boundingBox->getAltitudes();

        $this->assertIsArray($array);
    }
    
    public function testCannotSetAltitudes(): void
    {
        $this->boundingBox->setAltitudes(100.0, 0.0);
        
        $this->expectException(BoundingBoxException::class);
        $this->expectExceptionMessage('The bounding box\'s altitudes are already set.');

        $this->boundingBox->setAltitudes(100.0, 0.0);
    }
    
    public function testGetAltitudes(): void
    {
        $this->boundingBox->setAltitudes(100.0, 0.0);

        $array = $this->boundingBox->getAltitudes();

        $this->assertIsArray($array);
        $this->assertCount(2, $array);
        $this->assertSame(100.0, $array['minimum_altitude']);
        $this->assertSame(0.0, $array['maximum_altitude']);
    }
    
    public function testCannotGetAltitudes(): void
    {
        $this->expectException(BoundingBoxException::class);
        $this->expectExceptionMessage('The bounding box\'s altitudes are not set.');
        
        $this->boundingBox->getAltitudes();
    }

    public function testToArray2DBBox(): void
    {
        $this->point->setPoints(123.456, 456.789);
        $this->point2->setPoints(789.012, 012.345);

        $this->boundingBox->setPoints($this->point, $this->point2);
        
        $array = $this->boundingBox->toArray();
        
        $this->assertIsArray($array);
        $this->assertCount(4, $array);
        $this->assertSame([123.456, 456.789, 789.012, 012.345], $array);
    }
    
    public function testToArray2DBBoxExcepts(): void
    {
        $this->expectException(BoundingBoxException::class);
        $this->expectExceptionMessage('The bounding box\'s Points are not set.');

        $array = $this->boundingBox->toArray();
    }
    
    public function testToArray3DBBox(): void
    {
        $this->point->setPoints(123.456, 456.789);
        $this->point2->setPoints(789.012, 012.345);

        $this->boundingBox->setPoints($this->point, $this->point2);
        $this->boundingBox->setAltitudes(100, 0.0);

        $array = $this->boundingBox->toArray();

        $this->assertIsArray($array);
        $this->assertCount(6, $array);
        $this->assertSame([123.456, 456.789, 100.0, 789.012, 012.345, 0.0], $array);
    }

    /**
     * @throws JsonException
     */
    public function testToJson(): void
    {
        $this->point->setPoints(123.456, 456.789);
        $this->point2->setPoints(789.012, 012.345);

        $this->boundingBox->setPoints($this->point, $this->point2);
        
        $json = $this->boundingBox->toJson();
        
        $this->assertJson($json);
        $this->assertSame('[123.456,456.789,789.012,12.345]', $json);
    }
}
