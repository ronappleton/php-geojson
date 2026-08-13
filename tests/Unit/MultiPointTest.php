<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\Factory;
use RonAppleton\GeoJson\Objects\MultiPoint;
use RonAppleton\GeoJson\Objects\Point;

class MultiPointTest extends TestCase
{
    public function testAddPoint(): void
    {
        $point = Factory::make(GeoJsonType::Point);
        $point->setPoints(123.456, 45.678);

        $multiPoint = Factory::make(GeoJsonType::MultiPoint);
        $multiPoint->addPoint($point);

        $this->assertInstanceOf(MultiPoint::class, $multiPoint);
        $this->assertCount(1, $multiPoint->getPoints());
    }

    public function testToArray(): void
    {
        [$point, $point2] = Factory::make(GeoJsonType::Point, 2);

        $point->setPoints(100.0, 0.0);
        $point2->setPoints(101.0, 1.0);

        $multiPoint = Factory::make(GeoJsonType::MultiPoint);
        $multiPoint->addPoint($point);
        $multiPoint->addPoint($point2);

        $this->assertSame(
            [
                'type' => 'MultiPoint',
                'coordinates' => [[100.0, 0.0], [101.0, 1.0]],
            ],
            $multiPoint->toArray(),
        );
    }
}
