<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Tests\Unit;

use JsonException;
use PHPUnit\Framework\TestCase;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Objects\BoundingBox;
use RonAppleton\GeoJson\Objects\Factory;
use RonAppleton\GeoJson\Objects\Feature;
use RonAppleton\GeoJson\Exceptions\Feature as FeatureException;
use RonAppleton\GeoJson\Objects\Point;
use RonAppleton\GeoJson\Objects\Polygon;

use function json_encode;

use const JSON_THROW_ON_ERROR;

/**
 * @phpcs:disable SlevomatCodingStandard.Files.FunctionLength.FunctionLength
 * @phpcs:disable SlevomatCodingStandard.Functions.FunctionLength.FunctionLength
 */
class FeatureTest extends TestCase
{
    public function testSetId(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);
        
        $this->assertInstanceOf(Feature::class, $feature);
        
        $feature->setId('testId');
    }
    
    public function testCannotSetId(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);

        $this->assertInstanceOf(Feature::class, $feature);

        $feature->setId('testId');
        
        $this->expectException(FeatureException::class);
        $this->expectExceptionMessage('The id is already set on this Feature.');
        
        $feature->setId('testId2');
    }
    
    public function testGetId(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);

        $this->assertInstanceOf(Feature::class, $feature);
        
        $feature->setId('test');
        
        $id = $feature->getId();
        
        $this->assertSame('test', $id);
    }
    
    public function testCannotGetId(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);

        $this->assertInstanceOf(Feature::class, $feature);
        
        $this->expectException(FeatureException::class);
        $this->expectExceptionMessage('The id is not set on this Feature.');
        
        $feature->getId();
    }

    public function testSetBoundingBox(): void
    {
        $boundingBox = Factory::make(GeoJsonType::BoundingBox);
        $feature = Factory::make(GeoJsonType::Feature);
        
        $this->assertInstanceOf(BoundingBox::class, $boundingBox);
        $this->assertInstanceOf(Feature::class, $feature);
        
        $feature->setBoundingBox($boundingBox);
    }

    public function testCannotSetBoundingBox(): void
    {
        $boundingBox = Factory::make(GeoJsonType::BoundingBox);
        $feature = Factory::make(GeoJsonType::Feature);

        $this->assertInstanceOf(BoundingBox::class, $boundingBox);
        $this->assertInstanceOf(Feature::class, $feature);

        $feature->setBoundingBox($boundingBox);
        
        $this->expectException(FeatureException::class);
        $this->expectExceptionMessage('The bounding box is already set.');
        $feature->setBoundingBox($boundingBox);
    }

    public function testGetBoundingBox(): void
    {
        $boundingBox = Factory::make(GeoJsonType::BoundingBox);
        $feature = Factory::make(GeoJsonType::Feature);

        $this->assertInstanceOf(BoundingBox::class, $boundingBox);
        $this->assertInstanceOf(Feature::class, $feature);

        $feature->setBoundingBox($boundingBox);
        
        $feature->getBoundingBox();
    }

    public function testCannotGetBoundingBox(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);

        $this->assertInstanceOf(Feature::class, $feature);

        $this->expectException(FeatureException::class);
        $this->expectExceptionMessage('The bounding box is not set on this Feature.');
        
        $feature->getBoundingBox();
    }
    
    public function testSetGeometry(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);
        $point = Factory::make(GeoJsonType::Point);

        $this->assertInstanceOf(Feature::class, $feature);
        $this->assertInstanceOf(Point::class, $point);
        
        $feature->setGeometry($point);
    }
    
    public function testCannotSetGeometry(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);
        $point = Factory::make(GeoJsonType::Point);

        $this->assertInstanceOf(Feature::class, $feature);
        $this->assertInstanceOf(Point::class, $point);

        $feature->setGeometry($point);
        
        $this->expectException(FeatureException::class);
        $this->expectExceptionMessage('The geometry is already set on this Feature.');
        
        $feature->setGeometry($point);
    }
    
    public function testGetGeometry(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);
        $point = Factory::make(GeoJsonType::Point);

        $this->assertInstanceOf(Feature::class, $feature);
        $this->assertInstanceOf(Point::class, $point);

        $feature->setGeometry($point);
        $feature->getGeometry();
    }
    
    public function testCannotGetGeometry(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);

        $this->assertInstanceOf(Feature::class, $feature);
        
        $this->expectException(FeatureException::class);
        $this->expectExceptionMessage('The geometry is not set on this Feature.');
        
        $feature->getGeometry();
    }
    
    public function testSetProperties(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);
        
        $this->assertInstanceOf(Feature::class, $feature);
        
        $properties = [
            'name' => 'someplace',
            'object' => [
                'image' => 'someimage',
            ],
        ];
        
        $feature = $feature->setProperties($properties);

        $this->assertInstanceOf(Feature::class, $feature);
        
        $feature = $feature->setProperties(['new' => 'property']);
        
        $this->assertInstanceOf(Feature::class, $feature);
        
    }
    
    public function testCannotSetProperties(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);

        $this->assertInstanceOf(Feature::class, $feature);

        $properties = [
            'name' => 'someplace',
            'object' => [
                'image' => 'someimage',
            ],
        ];

        $feature->setProperties($properties);

        $properties2 = [
            'object' => 'bar',
        ];
        
        $this->expectException(FeatureException::class);
        $this->expectExceptionMessage('The object property is already set.');
        
        $feature->setProperties($properties2);
    }

    public function testGetProperties(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);

        $this->assertInstanceOf(Feature::class, $feature);
        
        $properties = [
            'name' => 'someplace',
            'object' => [
                'image' => 'someimage',
            ],
        ];
        
        $feature->setProperties($properties);
        $feature->getProperties();
    }

    public function testCannotGetProperties(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);

        $this->assertInstanceOf(Feature::class, $feature);
        
        $this->expectException(FeatureException::class);
        $this->expectExceptionMessage('No properties are set on this Feature.');
        
        $feature->getProperties();
    }

    public function testSetProperty(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);

        $this->assertInstanceOf(Feature::class, $feature);
        
        $feature->setProperty('name', 'ron');
    }

    public function testCannotSetProperty(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);

        $this->assertInstanceOf(Feature::class, $feature);

        $this->expectException(FeatureException::class);
        $this->expectExceptionMessage('The name property is already set.');
        
        $feature->setProperty('name', 'ron');
        $feature->setProperty('name', 'john');
    }
    
    public function testGetProperty(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);

        $this->assertInstanceOf(Feature::class, $feature);
        
        $feature->setProperty('name', 'ron');
        $feature->getProperty('name');
    }
    
    public function testCannotGetProperty(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);

        $this->assertInstanceOf(Feature::class, $feature);

        $this->expectException(FeatureException::class);
        $this->expectExceptionMessage('The name property is not set.');
        
        $feature->getProperty('name');
    }

    /**
     * @throws JsonException
     */
    public function testToArrayAndToJson(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);
        $boundingBox = Factory::make(GeoJsonType::BoundingBox);
        [$point, $point2, $pointP, $pointP2, $pointP3, $pointP4] = Factory::make(GeoJsonType::Point, 6);
        $polygon = Factory::make(GeoJsonType::Polygon);
        
        $this->assertInstanceOf(Feature::class, $feature);
        $this->assertInstanceOf(BoundingBox::class, $boundingBox);
        $this->assertInstanceOf(Point::class, $point);
        $this->assertInstanceOf(Point::class, $point2);
        $this->assertInstanceOf(Point::class, $pointP);
        $this->assertInstanceOf(Point::class, $pointP2);
        $this->assertInstanceOf(Point::class, $pointP3);
        $this->assertInstanceOf(Point::class, $pointP4);
        $this->assertInstanceOf(Polygon::class, $polygon);
        
        $point->setPoints(- 80.5, 31.54);
        $point2->setPoints( 90.5, 3.54);
        $boundingBox->setPoints($point, $point2);
        $feature->setBoundingBox($boundingBox);
        
        $pointP->setPoints(- 81.5, 31.54);
        $pointP2->setPoints(- 82.5, 35.54);
        $pointP3->setPoints(- 83.5, 39.54);
        $pointP4->setPoints(- 84.5, 32.54);
        $polygon->setPoints($pointP, $pointP2, $pointP3, $pointP4);
        $feature->setGeometry($polygon);
        
        $properties = [
            'prop0' => 'value0',
            'prop1' => [
                'this' => 'that',
            ],
        ];
        
        $feature->setProperties($properties);
        
        $array = $feature->toArray();
        
        $expectedArray = [
            'type' => 'Feature',
            'geometry' => [
                [
                    - 81.5, 31.54,
                ],
                [
                    - 82.5, 35.54,
                ],
                [
                    - 83.5, 39.54,
                ],
                [
                    - 84.5, 32.54,
                ],
            ],
            'properties' => [
                'prop0' => 'value0',
                'prop1' => [
                    'this' => 'that',
                ],
            ],
            'bbox' => [
                - 80.5, 31.54, 90.5, 3.54,
            ],
        ];
        
        $this->assertSame($expectedArray, $array);
        
        $json = $feature->toJson();
        
        $this->assertSame(json_encode($expectedArray, JSON_THROW_ON_ERROR), $json);
    }
}
