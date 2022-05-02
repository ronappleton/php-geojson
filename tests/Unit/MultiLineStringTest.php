<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Tests\Unit;

use JsonException;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\Factory;
use RonAppleton\GeoJson\Objects\LineString;
use RonAppleton\GeoJson\Objects\MultiLineString;
use RonAppleton\GeoJson\Objects\Point;

class MultiLineStringTest extends \PHPUnit\Framework\TestCase
{
    public function testSetAndGet(): void
    {
        [$lineString, $lineString2, $lineString3] = Factory::make(GeoJsonType::LineString, 3);
        
        $this->assertInstanceOf(LineString::class, $lineString);
        $this->assertInstanceOf(LineString::class, $lineString2);
        $this->assertInstanceOf(LineString::class, $lineString3);
        
        [$lineStringPoint, $lineStringPoint2, $lineStringPoint3, $lineStringPoint4, $lineStringPoint5] =
            Factory::make(GeoJsonType::Point, 5);
        
        $this->assertInstanceOf(Point::class, $lineStringPoint);
        $this->assertInstanceOf(Point::class, $lineStringPoint2);
        $this->assertInstanceOf(Point::class, $lineStringPoint3);
        $this->assertInstanceOf(Point::class, $lineStringPoint4);
        $this->assertInstanceOf(Point::class, $lineStringPoint5);
        
        $lineStringPoint->setPoints(-101.5, 39.662);
        $lineStringPoint2->setPoints(-101.75, 39.2415);
        $lineStringPoint3->setPoints(-101.23, 39.2415);
        $lineStringPoint4->setPoints(-101.749, 39.7984);
        $lineStringPoint5->setPoints(-101.5, 39.011);
        
        $lineString->addPoints(
            $lineStringPoint,
            $lineStringPoint2,
            $lineStringPoint3,
            $lineStringPoint4,
            $lineStringPoint5,
        );
        
        [$lineString2Point, $lineString2Point2, $lineString2Point3] = Factory::make(GeoJsonType::Point, 3);
        
        $this->assertInstanceOf(Point::class, $lineString2Point);
        $this->assertInstanceOf(Point::class, $lineString2Point2);
        $this->assertInstanceOf(Point::class, $lineString2Point3);
        
        $lineString2Point->setPoints(-99.23, 38.6605);
        $lineString2Point2->setPoints(-99.56, 38.727);
        $lineString2Point3->setPoints(-99.25, 38.018);
        
        $lineString2->addPoints(
            $lineString2Point,
            $lineString2Point2,
            $lineString2Point3,
        );
        
        [$lineString3Point, $lineString3Point2, $lineString3Point3, $lineString3Point4] =
            Factory::make(GeoJsonType::Point, 4);
        
        $this->assertInstanceOf(Point::class, $lineString3Point);
        $this->assertInstanceOf(Point::class, $lineString3Point2);
        $this->assertInstanceOf(Point::class, $lineString3Point3);
        $this->assertInstanceOf(Point::class, $lineString3Point4);
        
        $lineString3Point->setPoints(-98.499, 38.913);
        $lineString3Point2->setPoints(-98.499, 38.913);
        $lineString3Point3->setPoints(-98.38, 38.15);
        $lineString3Point4->setPoints(-97.5, 38.629);
        
        $lineString3->addPoints(
            $lineString3Point,
            $lineString3Point2,
            $lineString3Point3,
            $lineString3Point4,
        );
        
        $multiLineString = Factory::make(GeoJsonType::MultiLineString);
        
        $this->assertInstanceOf(MultiLineString::class, $multiLineString);
        
        $multiLineString->setLineStrings($lineString, $lineString2, $lineString3);
        
        $array = $multiLineString->getLineStrings();
        $this->assertIsArray($array);
        $this->assertCount(3, $array);
    }

    public function testToArray(): void
    {
        [$lineString, $lineString2, $lineString3] = Factory::make(GeoJsonType::LineString, 3);

        $this->assertInstanceOf(LineString::class, $lineString);
        $this->assertInstanceOf(LineString::class, $lineString2);
        $this->assertInstanceOf(LineString::class, $lineString3);

        [$lineStringPoint, $lineStringPoint2, $lineStringPoint3, $lineStringPoint4, $lineStringPoint5] =
            Factory::make(GeoJsonType::Point, 5);

        $this->assertInstanceOf(Point::class, $lineStringPoint);
        $this->assertInstanceOf(Point::class, $lineStringPoint2);
        $this->assertInstanceOf(Point::class, $lineStringPoint3);
        $this->assertInstanceOf(Point::class, $lineStringPoint4);
        $this->assertInstanceOf(Point::class, $lineStringPoint5);

        $lineStringPoint->setPoints(-101.5, 39.662);
        $lineStringPoint2->setPoints(-101.75, 39.2415);
        $lineStringPoint3->setPoints(-101.23, 39.2415);
        $lineStringPoint4->setPoints(-101.749, 39.7984);
        $lineStringPoint5->setPoints(-101.5, 39.011);

        $lineString->addPoints(
            $lineStringPoint,
            $lineStringPoint2,
            $lineStringPoint3,
            $lineStringPoint4,
            $lineStringPoint5,
        );

        [$lineString2Point, $lineString2Point2, $lineString2Point3] = Factory::make(GeoJsonType::Point, 3);

        $this->assertInstanceOf(Point::class, $lineString2Point);
        $this->assertInstanceOf(Point::class, $lineString2Point2);
        $this->assertInstanceOf(Point::class, $lineString2Point3);

        $lineString2Point->setPoints(-99.23, 38.6605);
        $lineString2Point2->setPoints(-99.56, 38.727);
        $lineString2Point3->setPoints(-99.25, 38.018);

        $lineString2->addPoints(
            $lineString2Point,
            $lineString2Point2,
            $lineString2Point3,
        );

        [$lineString3Point, $lineString3Point2, $lineString3Point3, $lineString3Point4] =
            Factory::make(GeoJsonType::Point, 4);

        $this->assertInstanceOf(Point::class, $lineString3Point);
        $this->assertInstanceOf(Point::class, $lineString3Point2);
        $this->assertInstanceOf(Point::class, $lineString3Point3);
        $this->assertInstanceOf(Point::class, $lineString3Point4);

        $lineString3Point->setPoints(-98.499, 38.913);
        $lineString3Point2->setPoints(-98.499, 38.913);
        $lineString3Point3->setPoints(-98.38, 38.15);
        $lineString3Point4->setPoints(-97.5, 38.629);

        $lineString3->addPoints(
            $lineString3Point,
            $lineString3Point2,
            $lineString3Point3,
            $lineString3Point4,
        );

        $multiLineString = Factory::make(GeoJsonType::MultiLineString);

        $this->assertInstanceOf(MultiLineString::class, $multiLineString);

        $multiLineString->setLineStrings($lineString, $lineString2, $lineString3);
        
        $array = $multiLineString->toArray();
        
        $this->assertIsArray($array);
        $this->assertSame(
            [
                [
                    [-101.5, 39.662],
                    [-101.75, 39.2415],
                    [-101.23, 39.2415],
                    [-101.749, 39.7984],
                    [-101.5, 39.011]
                ],
                [
                    [-99.23, 38.6605],
                    [-99.56, 38.727],
                    [-99.25, 38.018]
                ],
                [
                    [-98.499, 38.913],
                    [-98.499, 38.913],
                    [-98.38, 38.15],
                    [-97.5, 38.629]
                ],
            ],
            $array
        );
    }

    /**
     * @throws JsonException
     */
    public function testToJson(): void
    {
        [$lineString, $lineString2, $lineString3] = Factory::make(GeoJsonType::LineString, 3);

        $this->assertInstanceOf(LineString::class, $lineString);
        $this->assertInstanceOf(LineString::class, $lineString2);
        $this->assertInstanceOf(LineString::class, $lineString3);

        [$lineStringPoint, $lineStringPoint2, $lineStringPoint3, $lineStringPoint4, $lineStringPoint5] =
            Factory::make(GeoJsonType::Point, 5);

        $this->assertInstanceOf(Point::class, $lineStringPoint);
        $this->assertInstanceOf(Point::class, $lineStringPoint2);
        $this->assertInstanceOf(Point::class, $lineStringPoint3);
        $this->assertInstanceOf(Point::class, $lineStringPoint4);
        $this->assertInstanceOf(Point::class, $lineStringPoint5);

        $lineStringPoint->setPoints(-101.5, 39.662);
        $lineStringPoint2->setPoints(-101.75, 39.2415);
        $lineStringPoint3->setPoints(-101.23, 39.2415);
        $lineStringPoint4->setPoints(-101.749, 39.7984);
        $lineStringPoint5->setPoints(-101.5, 39.011);

        $lineString->addPoints(
            $lineStringPoint,
            $lineStringPoint2,
            $lineStringPoint3,
            $lineStringPoint4,
            $lineStringPoint5,
        );

        [$lineString2Point, $lineString2Point2, $lineString2Point3] = Factory::make(GeoJsonType::Point, 3);

        $this->assertInstanceOf(Point::class, $lineString2Point);
        $this->assertInstanceOf(Point::class, $lineString2Point2);
        $this->assertInstanceOf(Point::class, $lineString2Point3);

        $lineString2Point->setPoints(-99.23, 38.6605);
        $lineString2Point2->setPoints(-99.56, 38.727);
        $lineString2Point3->setPoints(-99.25, 38.018);

        $lineString2->addPoints(
            $lineString2Point,
            $lineString2Point2,
            $lineString2Point3,
        );

        [$lineString3Point, $lineString3Point2, $lineString3Point3, $lineString3Point4] =
            Factory::make(GeoJsonType::Point, 4);

        $this->assertInstanceOf(Point::class, $lineString3Point);
        $this->assertInstanceOf(Point::class, $lineString3Point2);
        $this->assertInstanceOf(Point::class, $lineString3Point3);
        $this->assertInstanceOf(Point::class, $lineString3Point4);

        $lineString3Point->setPoints(-98.499, 38.913);
        $lineString3Point2->setPoints(-98.499, 38.913);
        $lineString3Point3->setPoints(-98.38, 38.15);
        $lineString3Point4->setPoints(-97.5, 38.629);

        $lineString3->addPoints(
            $lineString3Point,
            $lineString3Point2,
            $lineString3Point3,
            $lineString3Point4,
        );

        $multiLineString = Factory::make(GeoJsonType::MultiLineString);

        $this->assertInstanceOf(MultiLineString::class, $multiLineString);

        $multiLineString->setLineStrings($lineString, $lineString2, $lineString3);

        $json = $multiLineString->toJson();

        $this->assertJson($json);
        $this->assertSame(
            json_encode(
                [
                    [
                        [-101.5, 39.662],
                        [-101.75, 39.2415],
                        [-101.23, 39.2415],
                        [-101.749, 39.7984],
                        [-101.5, 39.011]
                    ],
                    [
                        [-99.23, 38.6605],
                        [-99.56, 38.727],
                        [-99.25, 38.018]
                    ],
                    [
                        [-98.499, 38.913],
                        [-98.499, 38.913],
                        [-98.38, 38.15],
                        [-97.5, 38.629]
                    ],
                ],
                JSON_THROW_ON_ERROR
            ),
            $json
        );
    }
}
