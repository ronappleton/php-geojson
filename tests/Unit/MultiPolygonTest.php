<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\Factory;
use RonAppleton\GeoJson\Objects\MultiPolygon;
use RonAppleton\GeoJson\Objects\Point;
use RonAppleton\GeoJson\Objects\Polygon;

use function json_encode;

use const JSON_THROW_ON_ERROR;

/**
 * @phpcs:disable SlevomatCodingStandard.Files.FunctionLength.FunctionLength
 * @phpcs:disable SlevomatCodingStandard.Functions.FunctionLength.FunctionLength
 */
class MultiPolygonTest extends TestCase
{
    public function testSetAndGetPolygons(): void
    {
        [$polygon, $polygon2] = Factory::make(GeoJsonType::Polygon, 2);
        
        $this->assertInstanceOf(Polygon::class, $polygon);
        $this->assertInstanceOf(Polygon::class, $polygon2);

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
        
        [$point6, $point7, $point8, $point9] = Factory::make(GeoJsonType::Point, 5);

        $this->assertInstanceOf(Point::class, $point6);
        $this->assertInstanceOf(Point::class, $point7);
        $this->assertInstanceOf(Point::class, $point8);
        $this->assertInstanceOf(Point::class, $point9);

        $point6->setPoints(111.111, 222.222);
        $point7->setPoints(333.333, 444.444);
        $point8->setPoints(555.555, 666.666);
        $point9->setPoints(777.777, 888.888);
        
        $polygon2->setPoints($point6, $point7, $point8, $point9);
        
        $multipolygon = Factory::make(GeoJsonType::MultiPolygon);
        
        $this->assertInstanceOf(MultiPolygon::class, $multipolygon);
        
        $multipolygon->setPolygons($polygon, $polygon2);
        
        $polygons = $multipolygon->getPolygons();
        $this->assertIsArray($polygons);
        $this->assertCount(2, $polygons);
    }

    public function testToArray(): void
    {
        [$polygon, $polygon2] = Factory::make(GeoJsonType::Polygon, 2);

        $this->assertInstanceOf(Polygon::class, $polygon);
        $this->assertInstanceOf(Polygon::class, $polygon2);

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

        [$point6, $point7, $point8, $point9] = Factory::make(GeoJsonType::Point, 5);

        $this->assertInstanceOf(Point::class, $point6);
        $this->assertInstanceOf(Point::class, $point7);
        $this->assertInstanceOf(Point::class, $point8);
        $this->assertInstanceOf(Point::class, $point9);

        $point6->setPoints(111.111, 222.222);
        $point7->setPoints(333.333, 444.444);
        $point8->setPoints(555.555, 666.666);
        $point9->setPoints(777.777, 888.888);

        $polygon2->setPoints($point6, $point7, $point8, $point9);

        $multipolygon = Factory::make(GeoJsonType::MultiPolygon);

        $this->assertInstanceOf(MultiPolygon::class, $multipolygon);

        $multipolygon->setPolygons($polygon, $polygon2);
        
        $array = $multipolygon->toArray();
        
        $this->assertSame(
            [
                [
                    [
                        123.456, 456.789,
                    ],
                    [
                        789.012, 12.345,
                    ],
                    [
                        345.678, 678.901,
                    ],
                    [
                        901.234, 234.567,
                    ],
                    [
                        567.89, 890.123,
                    ],
                ],
                [
                    [
                        111.111, 222.222,
                    ],
                    [
                        333.333, 444.444,
                    ],
                    [
                        555.555, 666.666,
                    ],
                    [
                        777.777, 888.888,
                    ],
                ],
            ],
            $array,
        );
    }

    public function testToJson(): void
    {
        [$polygon, $polygon2] = Factory::make(GeoJsonType::Polygon, 2);

        $this->assertInstanceOf(Polygon::class, $polygon);
        $this->assertInstanceOf(Polygon::class, $polygon2);

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

        [$point6, $point7, $point8, $point9] = Factory::make(GeoJsonType::Point, 5);

        $this->assertInstanceOf(Point::class, $point6);
        $this->assertInstanceOf(Point::class, $point7);
        $this->assertInstanceOf(Point::class, $point8);
        $this->assertInstanceOf(Point::class, $point9);

        $point6->setPoints(111.111, 222.222);
        $point7->setPoints(333.333, 444.444);
        $point8->setPoints(555.555, 666.666);
        $point9->setPoints(777.777, 888.888);

        $polygon2->setPoints($point6, $point7, $point8, $point9);

        $multipolygon = Factory::make(GeoJsonType::MultiPolygon);

        $this->assertInstanceOf(MultiPolygon::class, $multipolygon);

        $multipolygon->setPolygons($polygon, $polygon2);
        
        $json = $multipolygon->toJson();

        $jsonArray = json_encode(
            [
                [
                    [
                        123.456,
                        456.789,
                    ],
                    [
                        789.012,
                        12.345,
                    ],
                    [
                        345.678,
                        678.901,
                    ],
                    [
                        901.234,
                        234.567,
                    ],
                    [
                        567.89,
                        890.123,
                    ],
                ],
                [
                    [
                        111.111,
                        222.222,
                    ],
                    [
                        333.333,
                        444.444,
                    ],
                    [
                        555.555,
                        666.666,
                    ],
                    [
                        777.777,
                        888.888,
                    ],
                ],
            ],
            JSON_THROW_ON_ERROR,
        );
        $this->assertSame($jsonArray, $json);
    }
}
