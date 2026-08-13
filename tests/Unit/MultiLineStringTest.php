<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\Factory;
use RonAppleton\GeoJson\Objects\LineString;
use RonAppleton\GeoJson\Objects\MultiLineString;
use RonAppleton\GeoJson\Objects\Point;

class MultiLineStringTest extends TestCase
{
    public function testSetLineStrings(): void
    {
        [$point, $point2, $point3, $point4] = Factory::make(GeoJsonType::Point, 4);

        $point->setPoints(100.0, 0.0);
        $point2->setPoints(101.0, 1.0);
        $point3->setPoints(102.0, 0.0);
        $point4->setPoints(103.0, 1.0);

        $lineString = Factory::make(GeoJsonType::LineString);
        $lineString->addPoints($point, $point2);

        $lineString2 = Factory::make(GeoJsonType::LineString);
        $lineString2->addPoints($point3, $point4);

        $multiLineString = Factory::make(GeoJsonType::MultiLineString);
        $multiLineString->setLineStrings($lineString, $lineString2);

        $this->assertInstanceOf(MultiLineString::class, $multiLineString);
        $this->assertCount(2, $multiLineString->getLineStrings());
    }

    /**
     * @phpcs:disable SlevomatCodingStandard.Functions.FunctionLength.FunctionLength
     */
    public function testToArray(): void
    {
        [$point, $point2, $point3, $point4] = Factory::make(GeoJsonType::Point, 4);

        $point->setPoints(100.0, 0.0);
        $point2->setPoints(101.0, 1.0);
        $point3->setPoints(102.0, 0.0);
        $point4->setPoints(103.0, 1.0);

        $lineString = Factory::make(GeoJsonType::LineString);
        $lineString->addPoints($point, $point2);

        $lineString2 = Factory::make(GeoJsonType::LineString);
        $lineString2->addPoints($point3, $point4);

        $multiLineString = Factory::make(GeoJsonType::MultiLineString);
        $multiLineString->setLineStrings($lineString, $lineString2);

        $this->assertSame(
            [
                'type' => 'MultiLineString',
                'coordinates' => [
                    [[100.0, 0.0], [101.0, 1.0]],
                    [[102.0, 0.0], [103.0, 1.0]],
                ],
            ],
            $multiLineString->toArray(),
        );
    }
}
