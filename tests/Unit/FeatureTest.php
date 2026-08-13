<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Tests\Unit;

use JsonException;
use PHPUnit\Framework\TestCase;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Exceptions\Feature as FeatureException;
use RonAppleton\GeoJson\Objects\BoundingBox;
use RonAppleton\GeoJson\Objects\Factory;
use RonAppleton\GeoJson\Objects\Feature;
use RonAppleton\GeoJson\Objects\Point;

class FeatureTest extends TestCase
{
    public function testSetId(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);

        $this->assertInstanceOf(Feature::class, $feature);

        $feature->setId('testId');

        $this->assertSame('testId', $feature->getId());
    }

    public function testSetNumericId(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);

        $feature->setId(1001);

        $this->assertSame(1001, $feature->getId());
    }

    public function testCannotSetIdTwice(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);
        $feature->setId('testId');

        $this->expectException(FeatureException::class);
        $this->expectExceptionMessage('The id is already set on this Feature.');

        $feature->setId('testId2');
    }

    public function testCannotGetId(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);

        $this->expectException(FeatureException::class);
        $this->expectExceptionMessage('The id is not set on this Feature.');

        $feature->getId();
    }

    public function testSetGeometry(): void
    {
        $point = Factory::make(GeoJsonType::Point);
        $point->setPoints(100.0, 0.0);

        $feature = Factory::make(GeoJsonType::Feature);
        $feature->setGeometry($point);

        $this->assertSame($point, $feature->getGeometry());
    }

    public function testCannotSetGeometryTwice(): void
    {
        $point = Factory::make(GeoJsonType::Point);
        $point->setPoints(100.0, 0.0);

        $feature = Factory::make(GeoJsonType::Feature);
        $feature->setGeometry($point);

        $this->expectException(FeatureException::class);
        $this->expectExceptionMessage('The geometry is already set on this Feature.');

        $feature->setGeometry($point);
    }

    public function testCannotSetFeatureAsGeometry(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);
        $geometry = Factory::make(GeoJsonType::Feature);

        $this->expectException(FeatureException::class);
        $this->expectExceptionMessage('Only geometry objects may be set as the geometry of a Feature.');

        $feature->setGeometry($geometry);
    }

    public function testGetGeometryWhenNotSet(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);

        $this->assertNull($feature->getGeometry());
    }

    public function testSetGeometryToNull(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);
        $feature->setGeometry(null);

        $this->assertNull($feature->getGeometry());
    }

    public function testSetProperties(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);

        $feature->setProperties(['name' => 'someplace']);

        $this->assertSame(['name' => 'someplace'], $feature->getProperties());
    }

    public function testSetPropertiesMerges(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);

        $feature->setProperties(['name' => 'someplace']);
        $feature->setProperties(['description' => 'a description']);

        $this->assertSame(
            ['name' => 'someplace', 'description' => 'a description'],
            $feature->getProperties(),
        );
    }

    public function testCannotSetDuplicateProperty(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);
        $feature->setProperties(['name' => 'someplace']);

        $this->expectException(FeatureException::class);
        $this->expectExceptionMessage('The name property is already set.');

        $feature->setProperties(['name' => 'someplace else']);
    }

    public function testCannotGetPropertiesWhenNotSet(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);

        $this->expectException(FeatureException::class);
        $this->expectExceptionMessage('No properties are set on this Feature.');

        $feature->getProperties();
    }

    public function testSetProperty(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);

        $feature->setProperty('name', 'ron');

        $this->assertSame('ron', $feature->getProperty('name'));
    }

    public function testSetPropertyAllowsFalsyValues(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);

        $feature->setProperty('count', 0);
        $feature->setProperty('enabled', false);
        $feature->setProperty('name', '');

        $this->assertSame(0, $feature->getProperty('count'));
        $this->assertFalse($feature->getProperty('enabled'));
        $this->assertSame('', $feature->getProperty('name'));
    }

    public function testCannotSetPropertyTwice(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);
        $feature->setProperty('name', 'ron');

        $this->expectException(FeatureException::class);
        $this->expectExceptionMessage('The name property is already set.');

        $feature->setProperty('name', 'john');
    }

    public function testCannotGetPropertyWhenNotSet(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);

        $this->expectException(FeatureException::class);
        $this->expectExceptionMessage('The name property is not set.');

        $feature->getProperty('name');
    }

    public function testZeroIdIsAllowed(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);
        $feature->setId(0);

        $this->assertSame(0, $feature->getId());

        $array = $feature->toArray();

        $this->assertArrayHasKey('id', $array);
        $this->assertSame(0, $array['id']);
    }

    public function testToArray(): void
    {
        $point = Factory::make(GeoJsonType::Point);
        $point->setPoints(100.0, 0.0);

        $feature = Factory::make(GeoJsonType::Feature);
        $feature->setId('0001');
        $feature->setGeometry($point);
        $feature->setProperties(['prop0' => 'value0']);

        $this->assertSame(
            [
                'type' => 'Feature',
                'id' => '0001',
                'geometry' => [100.0, 0.0],
                'properties' => ['prop0' => 'value0'],
            ],
            $feature->toArray(),
        );
    }

    public function testToArrayWithoutGeometryOrProperties(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);

        $this->assertSame(
            [
                'type' => 'Feature',
                'geometry' => null,
                'properties' => null,
            ],
            $feature->toArray(),
        );
    }

    public function testToArrayWithBoundingBox(): void
    {
        $point = Factory::make(GeoJsonType::Point);
        $point->setPoints(100.0, 0.0);

        $boundingBox = Factory::make(GeoJsonType::BoundingBox);
        [$southwest, $northeast] = Factory::make(GeoJsonType::Point, 2);
        $southwest->setPoints(100.0, 0.0);
        $northeast->setPoints(101.0, 1.0);
        $boundingBox->setPoints($southwest, $northeast);

        $feature = Factory::make(GeoJsonType::Feature);
        $feature->setGeometry($point);
        $feature->setBoundingBox($boundingBox);

        $this->assertSame(
            [
                'type' => 'Feature',
                'geometry' => [100.0, 0.0],
                'properties' => null,
                'bbox' => [100.0, 0.0, 101.0, 1.0],
            ],
            $feature->toArray(),
        );
    }

    /**
     * @throws JsonException
     */
    public function testToJson(): void
    {
        $point = Factory::make(GeoJsonType::Point);
        $point->setPoints(100.0, 0.0);

        $feature = Factory::make(GeoJsonType::Feature);
        $feature->setGeometry($point);
        $feature->setProperties(['prop0' => 'value0']);

        $this->assertJsonStringEqualsJsonString(
            '{"type":"Feature","geometry":[100.0,0.0],"properties":{"prop0":"value0"}}',
            $feature->toJson(),
        );
    }
}
