<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Tests\Unit;

use JsonException;
use PHPUnit\Framework\TestCase;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Exceptions\Feature as FeatureException;
use RonAppleton\GeoJson\Exceptions\GeometryCollection as GeometryCollectionException;
use RonAppleton\GeoJson\Exceptions\Parse as ParseException;
use RonAppleton\GeoJson\Interfaces\GeoJsonObject as GeoJsonObjectInterface;
use RonAppleton\GeoJson\Objects\BoundingBox;
use RonAppleton\GeoJson\Objects\Factory;
use RonAppleton\GeoJson\Objects\Feature;
use RonAppleton\GeoJson\Objects\FeatureCollection;
use RonAppleton\GeoJson\Objects\GeometryCollection;
use RonAppleton\GeoJson\Objects\LineString;
use RonAppleton\GeoJson\Objects\MultiLineString;
use RonAppleton\GeoJson\Objects\MultiPoint;
use RonAppleton\GeoJson\Objects\MultiPolygon;
use RonAppleton\GeoJson\Objects\Parser;
use RonAppleton\GeoJson\Objects\Point;
use RonAppleton\GeoJson\Objects\Polygon;

/**
 * @phpcs:disable SlevomatCodingStandard.Functions.FunctionLength.FunctionLength
 */
class ParserTest extends TestCase
{
    public function testFromArrayReturnsPointForBarePosition(): void
    {
        $point = Parser::fromArray([100.0, 0.0]);

        $this->assertInstanceOf(Point::class, $point);
        $this->assertSame(100.0, $point->getLongitude());
        $this->assertSame(0.0, $point->getLatitude());
    }

    public function testFromArrayReturnsPointWithAltitudeForBarePosition(): void
    {
        $point = Parser::fromArray([100.0, 0.0, 10.0]);

        $this->assertInstanceOf(Point::class, $point);
        $this->assertSame(10.0, $point->getAltitude());
    }

    public function testFromJsonReturnsPointForBarePosition(): void
    {
        $point = Parser::fromJson('[100.0, 0.0]');

        $this->assertInstanceOf(Point::class, $point);
        $this->assertSame([100.0, 0.0], $point->toArray());
    }

    public function testExtraPositionElementsAreIgnored(): void
    {
        $point = Parser::fromJson('{"type":"Point","coordinates":[100.0, 0.0, 10.0, 999.0]}');

        $this->assertInstanceOf(Point::class, $point);
        $this->assertSame([100.0, 0.0, 10.0], $point->toArray());
    }

    public function testRoundTripPoint(): void
    {
        $point = $this->point(100.0, 0.0, 10.0);

        $this->assertRoundTrip($point);
    }

    public function testRoundTripMultiPoint(): void
    {
        $multiPoint = Factory::make(GeoJsonType::MultiPoint);
        $multiPoint->addPoint($this->point(100.0, 0.0));
        $multiPoint->addPoint($this->point(101.0, 1.0));

        $this->assertRoundTrip($multiPoint);
    }

    public function testRoundTripLineString(): void
    {
        $lineString = Factory::make(GeoJsonType::LineString);
        $lineString->addPoints($this->point(102.0, 0.0), $this->point(103.0, 1.0));

        $this->assertRoundTrip($lineString);
    }

    public function testRoundTripMultiLineString(): void
    {
        $lineString = Factory::make(GeoJsonType::LineString);
        $lineString->addPoints($this->point(102.0, 0.0), $this->point(103.0, 1.0));

        $multiLineString = Factory::make(GeoJsonType::MultiLineString);
        $multiLineString->setLineStrings($lineString);

        $this->assertRoundTrip($multiLineString);
    }

    public function testRoundTripPolygon(): void
    {
        $polygon = Factory::make(GeoJsonType::Polygon);
        $polygon->setExteriorRing(
            $this->point(100.0, 0.0),
            $this->point(101.0, 0.0),
            $this->point(101.0, 1.0),
            $this->point(100.0, 1.0),
            $this->point(100.0, 0.0),
        );
        $polygon->addInteriorRing(
            $this->point(100.2, 0.2),
            $this->point(100.8, 0.2),
            $this->point(100.8, 0.8),
            $this->point(100.2, 0.2),
        );

        $this->assertRoundTrip($polygon);
    }

    public function testRoundTripMultiPolygon(): void
    {
        $polygon = Factory::make(GeoJsonType::Polygon);
        $polygon->setExteriorRing(
            $this->point(100.0, 0.0),
            $this->point(101.0, 0.0),
            $this->point(101.0, 1.0),
            $this->point(100.0, 0.0),
        );

        $multiPolygon = Factory::make(GeoJsonType::MultiPolygon);
        $multiPolygon->setPolygons($polygon);

        $this->assertRoundTrip($multiPolygon);
    }

    public function testRoundTripGeometryCollection(): void
    {
        $collection = Factory::make(GeoJsonType::GeometryCollection);
        $collection->addGeometry($this->point(100.0, 0.0));

        $lineString = Factory::make(GeoJsonType::LineString);
        $lineString->addPoints($this->point(102.0, 0.0), $this->point(103.0, 1.0));

        $collection->addGeometry($lineString);

        $this->assertRoundTrip($collection);
    }

    public function testRoundTripFeature(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);
        $feature->setId('0001');
        $feature->setGeometry($this->point(100.0, 0.0));
        $feature->setProperties(['name' => 'somewhere']);

        $this->assertRoundTrip($feature);
    }

    public function testRoundTripFeatureCollection(): void
    {
        $feature = Factory::make(GeoJsonType::Feature);
        $feature->setId('0001');
        $feature->setGeometry($this->point(100.0, 0.0));

        $collection = Factory::make(GeoJsonType::FeatureCollection);
        $collection->addFeature($feature);

        $this->assertRoundTrip($collection);
    }

    public function testRoundTripWithBoundingBox(): void
    {
        $lineString = Factory::make(GeoJsonType::LineString);
        $lineString->addPoints($this->point(102.0, 0.0), $this->point(103.0, 1.0));

        [$southwest, $northeast] = Factory::make(GeoJsonType::Point, 2);
        $southwest->setPoints(102.0, 0.0);
        $northeast->setPoints(103.0, 1.0);

        $boundingBox = Factory::make(GeoJsonType::BoundingBox);
        $boundingBox->setPoints($southwest, $northeast);

        $lineString->setBoundingBox($boundingBox);

        $this->assertRoundTrip($lineString);
    }

    /**
     * @throws JsonException
     */
    public function testFromJsonMatchesRfc7946Example(): void
    {
        $json = '{
            "type": "FeatureCollection",
            "features": [
                {
                    "type": "Feature",
                    "properties": {"prop0": "value0"},
                    "geometry": {
                        "type": "Point",
                        "coordinates": [102.0, 0.5]
                    }
                },
                {
                    "type": "Feature",
                    "properties": {"prop0": "value0", "prop1": 0.0},
                    "geometry": {
                        "type": "LineString",
                        "coordinates": [
                            [102.0, 0.0],
                            [103.0, 1.0],
                            [104.0, 0.0],
                            [105.0, 1.0]
                        ]
                    }
                },
                {
                    "type": "Feature",
                    "properties": {"prop0": "value0", "prop1": {"this": "that"}},
                    "geometry": {
                        "type": "Polygon",
                        "coordinates": [
                            [
                                [100.0, 0.0],
                                [101.0, 0.0],
                                [101.0, 1.0],
                                [100.0, 0.0]
                            ]
                        ]
                    }
                }
            ]
        }';

        $featureCollection = Parser::fromJson($json);

        $this->assertInstanceOf(FeatureCollection::class, $featureCollection);

        $features = $featureCollection->getFeatures();

        $this->assertCount(3, $features);

        $this->assertInstanceOf(Point::class, $features[0]->getGeometry());
        $this->assertInstanceOf(LineString::class, $features[1]->getGeometry());
        $this->assertInstanceOf(Polygon::class, $features[2]->getGeometry());

        $this->assertSame('value0', $features[0]->getProperty('prop0'));
        $this->assertSame(['this' => 'that'], $features[2]->getProperty('prop1'));

        $this->assertRoundTrip($featureCollection);
    }

    public function testParsedObjectsHaveCorrectType(): void
    {
        $polygon = '{"type":"Polygon","coordinates":[[[100.0, 0.0], [101.0, 0.0], [101.0, 1.0], [100.0, 0.0]]]}';
        $multiPolygon = '{"type":"MultiPolygon","coordinates":[[[[100.0, 0.0], [101.0, 0.0]';
        $multiPolygon .= ', [101.0, 1.0], [100.0, 0.0]]]]}';

        $data = [
            '{"type":"Point","coordinates":[100.0, 0.0]}' => GeoJsonType::Point,
            '{"type":"MultiPoint","coordinates":[[100.0, 0.0]]}' => GeoJsonType::MultiPoint,
            '{"type":"LineString","coordinates":[[100.0, 0.0], [101.0, 1.0]]}' => GeoJsonType::LineString,
            '{"type":"MultiLineString","coordinates":[[[100.0, 0.0], [101.0, 1.0]]]}' => GeoJsonType::MultiLineString,
            $polygon => GeoJsonType::Polygon,
            $multiPolygon => GeoJsonType::MultiPolygon,
            '{"type":"GeometryCollection","geometries":[]}' => GeoJsonType::GeometryCollection,
            '{"type":"Feature","geometry":null,"properties":null}' => GeoJsonType::Feature,
            '{"type":"FeatureCollection","features":[]}' => GeoJsonType::FeatureCollection,
        ];

        foreach ($data as $json => $type) {
            $this->assertSame($type, Parser::fromJson($json)->getType(), $json);
        }
    }

    public function testFeatureWithoutGeometryOrProperties(): void
    {
        $feature = Parser::fromJson('{"type":"Feature"}');

        $this->assertInstanceOf(Feature::class, $feature);
        $this->assertNull($feature->getGeometry());

        $this->assertSame(
            [
                'type' => 'Feature',
                'geometry' => null,
                'properties' => null,
            ],
            $feature->toArray(),
        );
    }

    public function testFeatureWithNumericId(): void
    {
        $feature = Parser::fromJson('{"type":"Feature","id":1001}');

        $this->assertSame(1001, $feature->getId());
    }

    public function testFeatureWithZeroId(): void
    {
        $feature = Parser::fromJson('{"type":"Feature","id":0}');

        $this->assertSame(0, $feature->getId());

        $array = $feature->toArray();

        $this->assertArrayHasKey('id', $array);
        $this->assertSame(0, $array['id']);
    }

    public function testParsesBoundingBox(): void
    {
        $lineString = Parser::fromJson(
            '{"type":"LineString","coordinates":[[102.0, 0.0], [103.0, 1.0]],"bbox":[102.0, 0.0, 103.0, 1.0]}',
        );

        $this->assertInstanceOf(BoundingBox::class, $lineString->getBoundingBox());

        $this->assertSame(
            [
                'type' => 'LineString',
                'coordinates' => [[102.0, 0.0], [103.0, 1.0]],
                'bbox' => [102.0, 0.0, 103.0, 1.0],
            ],
            $lineString->toArray(),
        );
    }

    public function testParsesThreeDimensionalBoundingBox(): void
    {
        $json = '{"type":"LineString","coordinates":[[102.0, 0.0], [103.0, 1.0]]';
        $lineString = Parser::fromJson($json . ',"bbox":[102.0, 0.0, -4.0, 103.0, 1.0, 6.0]}');

        $boundingBox = $lineString->getBoundingBox();

        $this->assertInstanceOf(BoundingBox::class, $boundingBox);

        $altitudes = $boundingBox->getAltitudes();

        $this->assertSame(- 4.0, $altitudes['minimum_altitude']);
        $this->assertSame(6.0, $altitudes['maximum_altitude']);
    }

    public function testInvalidJsonThrows(): void
    {
        $this->expectException(JsonException::class);

        Parser::fromJson('{"type":');
    }

    public function testTopLevelScalarThrows(): void
    {
        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('GeoJSON must be a JSON object, int given.');

        Parser::fromJson('42');
    }

    public function testMissingTypeThrows(): void
    {
        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('A GeoJSON object must have a "type" member.');

        Parser::fromArray([]);
    }

    public function testUnknownTypeThrows(): void
    {
        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('Unknown GeoJSON type "Foo".');

        Parser::fromJson('{"type":"Foo"}');
    }

    public function testBoundingBoxAsTypeThrows(): void
    {
        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('Unknown GeoJSON type "bbox".');

        Parser::fromJson('{"type":"bbox"}');
    }

    public function testMissingCoordinatesThrows(): void
    {
        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('The "coordinates" member of a Point must be an array.');

        Parser::fromJson('{"type":"Point"}');
    }

    public function testCoordinatesNotArrayThrows(): void
    {
        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('The "coordinates" member of a Point must be an array.');

        Parser::fromJson('{"type":"Point","coordinates":"nope"}');
    }

    public function testNonNumericCoordinateThrows(): void
    {
        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('Position coordinates must be numbers, string given.');

        Parser::fromJson('{"type":"Point","coordinates":["a", "b"]}');
    }

    public function testPositionTooShortThrows(): void
    {
        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('A position must be an array of at least two numbers, 1 element(s) given.');

        Parser::fromJson('{"type":"Point","coordinates":[100.0]}');
    }

    public function testInvalidIdThrows(): void
    {
        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('The "id" member of a Feature must be a string or a number, bool given.');

        Parser::fromJson('{"type":"Feature","id":true}');
    }

    public function testInvalidPropertiesThrows(): void
    {
        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('The "properties" member of a Feature must be an object or null, string given.');

        Parser::fromJson('{"type":"Feature","properties":"nope"}');
    }

    public function testInvalidGeometryThrows(): void
    {
        $this->expectException(ParseException::class);
        $this->expectExceptionMessage(
            'The "geometry" member of a Feature must be a GeoJSON object or null, string given.',
        );

        Parser::fromJson('{"type":"Feature","geometry":"nope"}');
    }

    public function testFeatureAsGeometryThrows(): void
    {
        $this->expectException(FeatureException::class);
        $this->expectExceptionMessage('Only geometry objects may be set as the geometry of a Feature.');

        Parser::fromJson('{"type":"Feature","geometry":{"type":"Feature"}}');
    }

    public function testNonArrayGeometryInCollectionThrows(): void
    {
        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('The "geometries" member of a GeometryCollection must be an array.');

        Parser::fromJson('{"type":"GeometryCollection","geometries":["nope"]}');
    }

    public function testFeatureInGeometryCollectionThrows(): void
    {
        $this->expectException(GeometryCollectionException::class);
        $this->expectExceptionMessage('Only geometry objects may be added to a GeometryCollection.');

        Parser::fromJson('{"type":"GeometryCollection","geometries":[{"type":"Feature"}]}');
    }

    public function testNonFeatureInFeatureCollectionThrows(): void
    {
        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('The "features" member of a FeatureCollection must be an array.');

        Parser::fromJson('{"type":"FeatureCollection","features":[{"type":"Point","coordinates":[100.0, 0.0]}]}');
    }

    public function testInvalidBoundingBoxCountThrows(): void
    {
        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('The "bbox" member must be an array of four or six numbers.');

        Parser::fromJson('{"type":"Point","coordinates":[100.0, 0.0],"bbox":[100.0]}');
    }

    public function testNonArrayBoundingBoxThrows(): void
    {
        $this->expectException(ParseException::class);
        $this->expectExceptionMessage('The "bbox" member must be an array of four or six numbers.');

        Parser::fromJson('{"type":"Point","coordinates":[100.0, 0.0],"bbox":"nope"}');
    }

    public function testInvalidOutOfRangeCoordinateThrows(): void
    {
        $this->expectExceptionMessage('Longitude must be between -180 and 180, 200.5 given.');

        Parser::fromJson('{"type":"Point","coordinates":[200.5, 0.0]}');
    }

    private function assertRoundTrip(GeoJsonObjectInterface $object): void
    {
        $this->assertSame($object->toArray(), Parser::fromArray($object->toArray())->toArray());
    }

    private function point(float $longitude, float $latitude, ?float $altitude = null): Point
    {
        $point = Factory::make(GeoJsonType::Point);
        $point->setPoints($longitude, $latitude);

        if ($altitude !== null) {
            $point->setAltitude($altitude);
        }

        return $point;
    }
}
