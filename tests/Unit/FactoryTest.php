<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\Factory;
use RonAppleton\GeoJson\Objects\Factory as ObjectFactory;
use RonAppleton\GeoJson\Objects\Feature;
use RonAppleton\GeoJson\Objects\FeatureCollection;
use RonAppleton\GeoJson\Objects\GeometryCollection;
use RonAppleton\GeoJson\Objects\LineString;
use RonAppleton\GeoJson\Objects\MultiLineString;
use RonAppleton\GeoJson\Objects\MultiPoint;
use RonAppleton\GeoJson\Objects\MultiPolygon;
use RonAppleton\GeoJson\Objects\Point;
use RonAppleton\GeoJson\Objects\Polygon;

class FactoryTest extends TestCase
{
    public function testMakeFeature(): void
    {
        $feature = ObjectFactory::make(GeoJsonType::Feature);
        
        $this->assertInstanceOf(Feature::class, $feature);
    }
    
    public function testMakeFeatureCollection(): void
    {
        $featureCollection = ObjectFactory::make(GeoJsonType::FeatureCollection);

        $this->assertInstanceOf(FeatureCollection::class, $featureCollection);
    }
    
    public function testMakeGeometryCollection(): void
    {
        $geometryCollection = ObjectFactory::make(GeoJsonType::GeometryCollection);
        
        $this->assertInstanceOf(GeometryCollection::class, $geometryCollection);
    }
    
    public function testMakeLineString(): void
    {
        $linestring = ObjectFactory::make(GeoJsonType::LineString);
        
        $this->assertInstanceOf(LineString::class, $linestring);
    }
    
    public function testMakeMultiLineString(): void
    {
        $multiLineString = ObjectFactory::make(GeoJsonType::MultiLineString);
        
        $this->assertInstanceOf(MultiLineString::class, $multiLineString);
    }
    
    public function testMakeMultiPoint(): void
    {
        $multiPoint = ObjectFactory::make(GeoJsonType::MultiPoint);
        
        $this->assertInstanceOf(MultiPoint::class, $multiPoint);
    }
    
    public function testMakeMultiPolygon(): void
    {
        $multiPolygon = ObjectFactory::make(GeoJsonType::MultiPolygon);
        
        $this->assertInstanceOf(MultiPolygon::class, $multiPolygon);
    }
    
    public function testMakePoint(): void
    {
        $point = ObjectFactory::make(GeoJsonType::Point);
        
        $this->assertInstanceOf(Point::class, $point);
    }
    
    public function testMakePolygon(): void
    {
        $polygon = ObjectFactory::make(GeoJsonType::Polygon);
        
        $this->assertInstanceOf(Polygon::class, $polygon);
    }
    
    public function testMake2Points(): void
    {
        [$point, $point2] = Factory::make(GeoJsonType::Point, 2);
        
        $this->assertInstanceOf(Point::class, $point);
        $this->assertInstanceOf(Point::class, $point2);
    }
}
