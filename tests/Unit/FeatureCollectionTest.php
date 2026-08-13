<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Tests\Unit;

use JsonException;
use PHPUnit\Framework\TestCase;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\Factory;
use RonAppleton\GeoJson\Objects\Feature;
use RonAppleton\GeoJson\Objects\FeatureCollection;
use RonAppleton\GeoJson\Objects\LineString;
use RonAppleton\GeoJson\Objects\Point;
use RonAppleton\GeoJson\Objects\Polygon;

/**
 * @phpcs:disable SlevomatCodingStandard.Functions.FunctionLength.FunctionLength
 */
class FeatureCollectionTest extends TestCase
{
    public function testAddFeature(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);

        $featureCollection = Factory::make(GeoJsonType::FeatureCollection);
        $featureCollection->addFeature($feature);

        $this->assertInstanceOf(FeatureCollection::class, $featureCollection);
        $this->assertCount(1, $featureCollection->getFeatures());
    }

    public function testAddFeatures(): void
    {
        [$feature, $feature2] = Factory::make(GeoJsonType::Feature, 2);

        $featureCollection = Factory::make(GeoJsonType::FeatureCollection);
        $featureCollection->addFeatures($feature, $feature2);

        $this->assertCount(2, $featureCollection->getFeatures());
    }

    public function testToArray(): void
    {
        $point = Factory::make(GeoJsonType::Point);
        $point->setPoints(102.0, 0.5);

        $feature = Factory::make(GeoJsonType::Feature);
        $feature->setGeometry($point);
        $feature->setProperties(['prop0' => 'value0']);

        $featureCollection = Factory::make(GeoJsonType::FeatureCollection);
        $featureCollection->addFeature($feature);

        $this->assertSame(
            [
                'type' => 'FeatureCollection',
                'features' => [
                    [
                        'type' => 'Feature',
                        'geometry' => [102.0, 0.5],
                        'properties' => ['prop0' => 'value0'],
                    ],
                ],
            ],
            $featureCollection->toArray(),
        );
    }

    /**
     * @throws JsonException
     */
    public function testMatchesRfc7946Example(): void
    {
        $featureCollection = Factory::make(GeoJsonType::FeatureCollection);

        $point = Factory::make(GeoJsonType::Point);
        $point->setPoints(102.0, 0.5);

        $feature = Factory::make(GeoJsonType::Feature);
        $feature->setGeometry($point);
        $feature->setProperties(['prop0' => 'value0']);

        $featureCollection->addFeature($feature);

        $lineString = Factory::make(GeoJsonType::LineString);
        [$linePoint, $linePoint2, $linePoint3, $linePoint4] = Factory::make(GeoJsonType::Point, 4);
        $linePoint->setPoints(102.0, 0.0);
        $linePoint2->setPoints(103.0, 1.0);
        $linePoint3->setPoints(104.0, 0.0);
        $linePoint4->setPoints(105.0, 1.0);
        $lineString->addPoints($linePoint, $linePoint2, $linePoint3, $linePoint4);

        $feature2 = Factory::make(GeoJsonType::Feature);
        $feature2->setGeometry($lineString);
        $feature2->setProperties(['prop1' => 0.0, 'prop2' => 'value2']);

        $featureCollection->addFeature($feature2);

        $polygon = Factory::make(GeoJsonType::Polygon);
        [$polyPoint, $polyPoint2, $polyPoint3, $polyPoint4, $polyPoint5] = Factory::make(GeoJsonType::Point, 5);
        $polyPoint->setPoints(100.0, 0.0);
        $polyPoint2->setPoints(101.0, 0.0);
        $polyPoint3->setPoints(101.0, 1.0);
        $polyPoint4->setPoints(100.0, 1.0);
        $polyPoint5->setPoints(100.0, 0.0);
        $polygon->setExteriorRing($polyPoint, $polyPoint2, $polyPoint3, $polyPoint4, $polyPoint5);

        $feature3 = Factory::make(GeoJsonType::Feature);
        $feature3->setGeometry($polygon);
        $feature3->setProperties(['prop0' => 'value0', 'prop1' => ['this' => 'that']]);

        $featureCollection->addFeature($feature3);

        $this->assertJsonStringEqualsJsonString(
            '{
                "type": "FeatureCollection",
                "features": [
                    {
                        "type": "Feature",
                        "geometry": [102.0, 0.5],
                        "properties": {
                            "prop0": "value0"
                        }
                    },
                    {
                        "type": "Feature",
                        "geometry": {
                            "type": "LineString",
                            "coordinates": [
                                [102.0, 0.0],
                                [103.0, 1.0],
                                [104.0, 0.0],
                                [105.0, 1.0]
                            ]
                        },
                        "properties": {
                            "prop1": 0.0,
                            "prop2": "value2"
                        }
                    },
                    {
                        "type": "Feature",
                        "geometry": {
                            "type": "Polygon",
                            "coordinates": [
                                [
                                    [100.0, 0.0],
                                    [101.0, 0.0],
                                    [101.0, 1.0],
                                    [100.0, 1.0],
                                    [100.0, 0.0]
                                ]
                            ]
                        },
                        "properties": {
                            "prop0": "value0",
                            "prop1": {"this": "that"}
                        }
                    }
                ]
            }',
            $featureCollection->toJson(),
        );
    }
}
