<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Tests\Unit;

use JsonException;
use PHPUnit\Framework\TestCase;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\Factory;
use RonAppleton\GeoJson\Objects\Point;
use RonAppleton\GeoJson\Objects\Polygon;

class PolygonTest extends TestCase
{
    public function testAddAndGetPoints(): void
    {
        $polygon = Factory::make(GeoJsonType::Polygon);
        
        $this->assertInstanceOf(Polygon::class, $polygon);

        [$point, $point2, $point3, $point4, $point5] = Factory::make(GeoJsonType::Point, 5);

        $this->assertInstanceOf(Point::class, $point);
        $this->assertInstanceOf(Point::class, $point2);
        $this->assertInstanceOf(Point::class, $point3);
        $this->assertInstanceOf(Point::class, $point4);
        $this->assertInstanceOf(Point::class, $point5);
        
        $point->setPoints(123.456, 456.789);
        $point2->setPoints(789.012, 012.345);
        $point3->setPoints(345.678, 678.901);
        $point4->setPoints(901.234, 234.567);
        $point5->setPoints(567.890, 890.123);
        
        $polygon->setPoints($point, $point2, $point3, $point4, $point5);
        
        $points = $polygon->getPoints();
        $this->assertIsArray($points);
        $this->assertCount(5, $points);
    }

    public function testToArray(): void
    {
        $polygon = Factory::make(GeoJsonType::Polygon);

        $this->assertInstanceOf(Polygon::class, $polygon);

        [$point, $point2, $point3, $point4, $point5] = Factory::make(GeoJsonType::Point, 5);

        $this->assertInstanceOf(Point::class, $point);
        $this->assertInstanceOf(Point::class, $point2);
        $this->assertInstanceOf(Point::class, $point3);
        $this->assertInstanceOf(Point::class, $point4);
        $this->assertInstanceOf(Point::class, $point5);

        $point->setPoints(123.456, 456.789);
        $point2->setPoints(789.012, 012.345);
        $point3->setPoints(345.678, 678.901);
        $point4->setPoints(901.234, 234.567);
        $point5->setPoints(567.890, 890.123);

        $polygon->setPoints($point, $point2, $point3, $point4, $point5);
        
        $array = $polygon->toArray();
        $this->assertIsArray($array);
        $this->assertCount(5, $array);
        $this->assertSame(
            [
                [123.456, 456.789],
                [789.012, 012.345],
                [345.678, 678.901],
                [901.234, 234.567],
                [567.890, 890.123]
            ],
            $array
        );
    }

    /**
     * @throws JsonException
     */
    public function testToJson(): void
    {
        $polygon = Factory::make(GeoJsonType::Polygon);

        $this->assertInstanceOf(Polygon::class, $polygon);

        [$point, $point2, $point3, $point4, $point5] = Factory::make(GeoJsonType::Point, 5);

        $this->assertInstanceOf(Point::class, $point);
        $this->assertInstanceOf(Point::class, $point2);
        $this->assertInstanceOf(Point::class, $point3);
        $this->assertInstanceOf(Point::class, $point4);
        $this->assertInstanceOf(Point::class, $point5);

        $point->setPoints(123.456, 456.789);
        $point2->setPoints(789.012, 012.345);
        $point3->setPoints(345.678, 678.901);
        $point4->setPoints(901.234, 234.567);
        $point5->setPoints(567.890, 890.123);

        $polygon->setPoints($point, $point2, $point3, $point4, $point5);
        
        $json = $polygon->toJson();
        $this->assertJson($json);
        $this->assertSame(
            '[[123.456,456.789],[789.012,12.345],[345.678,678.901],[901.234,234.567],[567.89,890.123]]',
            $json,
        );
    }
}
