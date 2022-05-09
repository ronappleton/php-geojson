<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Tests\Unit;

use JsonException;
use PHPUnit\Framework\TestCase;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\Factory;
use RonAppleton\GeoJson\Objects\Feature;
use RonAppleton\GeoJson\Objects\Point;
use RonAppleton\GeoJson\Objects\Polygon;
use RonAppleton\GeoJson\Exceptions\Polygon as PolygonException;

/**
 * @phpcs:disable SlevomatCodingStandard.Files.FunctionLength.FunctionLength
 * @phpcs:disable SlevomatCodingStandard.Functions.FunctionLength.FunctionLength
 */
class PolygonTest extends TestCase
{
    private Polygon $polygon;
    private Point $point;
    private Point $point2;
    private Point $point3;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->polygon = Factory::make(GeoJsonType::Polygon);
        [$this->point, $this->point2, $this->point3] = Factory::make(GeoJsonType::Point, 3);

        $this->point->setPoints(123.456, 456.789);
        $this->point2->setPoints(789.012, 012.345);
        $this->point3->setPoints(789.012, 012.345);
    }
    
    public function testSetPolygonPoints(): void
    {
        $polygon = $this->polygon->setPoints($this->point, $this->point2);
        $this->assertInstanceOf(Polygon::class, $polygon);
    }
    
    public function testCannotSetPolygonPoints(): void
    {
        $this->polygon->setPoints($this->point, $this->point2);
        
        $this->expectException(PolygonException::class);
        $this->expectExceptionMessage('Polygons points are already set.');

        $this->polygon->setPoints($this->point3);
    }
    
    public function testGetPolygonPoints(): void
    {
        $polygon = $this->polygon->setPoints($this->point, $this->point2);
        $array = $polygon->getPoints();
        
        $this->assertIsArray($array);
        $this->assertCount(2, $array);
    }
    
    public function testCannotGetPolygonPoints(): void
    {
        $polygon = $this->polygon->setPoints($this->point);
        
        $this->expectException(PolygonException::class);
        $this->expectExceptionMessage('Polygons points are already set.');
        
        $polygon->setPoints($this->point);
    }

    public function testToArray(): void
    {
        $this->polygon->setPoints($this->point, $this->point2, $this->point3);
        
        $array = $this->polygon->toArray();
        
        $this->assertIsArray($array);
        $this->assertCount(3, $array);
        
        $this->assertSame(
            [
                [123.456, 456.789],
                [789.012, 012.345],
                [789.012, 012.345],
            ],
            $array,
        );
    }
}
