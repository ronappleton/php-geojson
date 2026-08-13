<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Tests\Unit;

use JsonException;
use PHPUnit\Framework\TestCase;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Exceptions\BoundingBox as BoundingBoxException;
use RonAppleton\GeoJson\Objects\BoundingBox;
use RonAppleton\GeoJson\Objects\Factory;
use RonAppleton\GeoJson\Objects\LineString;

class GeoJsonObjectTest extends TestCase
{
    public function testGetType(): void
    {
        $lineString = $this->makeLineString();

        $this->assertSame(GeoJsonType::LineString, $lineString->getType());
    }

    public function testSetAndGetBoundingBox(): void
    {
        $lineString = $this->makeLineString();
        $boundingBox = $this->makeBoundingBox();

        $lineString->setBoundingBox($boundingBox);

        $this->assertSame($boundingBox, $lineString->getBoundingBox());
    }

    public function testCannotSetBoundingBoxTwice(): void
    {
        $lineString = $this->makeLineString();
        $lineString->setBoundingBox($this->makeBoundingBox());

        $this->expectException(BoundingBoxException::class);
        $this->expectExceptionMessage('The bounding box is already set on this object.');

        $lineString->setBoundingBox($this->makeBoundingBox());
    }

    public function testToArrayWithBoundingBox(): void
    {
        $lineString = $this->makeLineString();
        $lineString->setBoundingBox($this->makeBoundingBox());

        $this->assertSame(
            [
                'type' => 'LineString',
                'coordinates' => [[100.0, 0.0], [101.0, 1.0]],
                'bbox' => [100.0, 0.0, 101.0, 1.0],
            ],
            $lineString->toArray(),
        );
    }

    /**
     * @throws JsonException
     */
    public function testToJsonWithBoundingBox(): void
    {
        $lineString = $this->makeLineString();
        $lineString->setBoundingBox($this->makeBoundingBox());

        $this->assertJsonStringEqualsJsonString(
            '{"type":"LineString","coordinates":[[100.0,0.0],[101.0,1.0]],"bbox":[100.0,0.0,101.0,1.0]}',
            $lineString->toJson(),
        );
    }

    private function makeBoundingBox(): BoundingBox
    {
        [$southwest, $northeast] = Factory::make(GeoJsonType::Point, 2);
        $southwest->setPoints(100.0, 0.0);
        $northeast->setPoints(101.0, 1.0);

        $boundingBox = Factory::make(GeoJsonType::BoundingBox);
        $boundingBox->setPoints($southwest, $northeast);

        return $boundingBox;
    }

    private function makeLineString(): LineString
    {
        [$point, $point2] = Factory::make(GeoJsonType::Point, 2);
        $point->setPoints(100.0, 0.0);
        $point2->setPoints(101.0, 1.0);

        $lineString = Factory::make(GeoJsonType::LineString);
        $lineString->addPoints($point, $point2);

        return $lineString;
    }
}
