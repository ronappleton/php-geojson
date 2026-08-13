<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Objects;

use JsonException;
use RonAppleton\GeoJson\Abstracts\GeoJsonObject;
use RonAppleton\GeoJson\Enums\GeoJsonType;
use RonAppleton\GeoJson\Enums\ParseExceptionType;
use RonAppleton\GeoJson\Exceptions\Parse as ParseException;
use RonAppleton\GeoJson\Interfaces\GeoJsonObject as GeoJsonObjectInterface;

use function array_is_list;
use function array_key_exists;
use function array_slice;
use function count;
use function get_debug_type;
use function is_array;
use function is_float;
use function is_int;
use function is_string;
use function json_decode;

use const JSON_THROW_ON_ERROR;

class Parser
{
    /**
     * @throws JsonException
     */
    public static function fromJson(string $json): GeoJsonObjectInterface
    {
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($data)) {
            throw new ParseException(ParseExceptionType::NotAnObject, get_debug_type($data));
        }

        return self::fromArray($data);
    }

    /**
     * @param array<int|string, mixed> $data
     */
    public static function fromArray(array $data): GeoJsonObjectInterface
    {
        if (self::isPosition($data)) {
            return self::position($data);
        }

        $type = self::type($data);

        return match ($type) {
            GeoJsonType::Point => self::parsePoint($data),
            GeoJsonType::MultiPoint => self::parseMultiPoint($data),
            GeoJsonType::LineString => self::parseLineString($data),
            GeoJsonType::MultiLineString => self::parseMultiLineString($data),
            GeoJsonType::Polygon => self::parsePolygon($data),
            GeoJsonType::MultiPolygon => self::parseMultiPolygon($data),
            GeoJsonType::GeometryCollection => self::parseGeometryCollection($data),
            GeoJsonType::Feature => self::parseFeature($data),
            GeoJsonType::FeatureCollection => self::parseFeatureCollection($data),
            default => throw new ParseException(ParseExceptionType::UnknownType, $type->value),
        };
    }

    /**
     * @param array<int|string, mixed> $data
     */
    private static function parsePoint(array $data): Point
    {
        $position = self::coordinates($data, GeoJsonType::Point);

        return self::withBoundingBox(self::position($position), $data);
    }

    /**
     * @param array<int|string, mixed> $data
     */
    private static function parseMultiPoint(array $data): MultiPoint
    {
        $multiPoint = Factory::make(GeoJsonType::MultiPoint);

        $points = self::points(self::coordinates($data, GeoJsonType::MultiPoint), GeoJsonType::MultiPoint);

        foreach ($points as $point) {
            $multiPoint->addPoint($point);
        }

        return self::withBoundingBox($multiPoint, $data);
    }

    /**
     * @param array<int|string, mixed> $data
     */
    private static function parseLineString(array $data): LineString
    {
        $lineString = Factory::make(GeoJsonType::LineString);

        $points = self::points(self::coordinates($data, GeoJsonType::LineString), GeoJsonType::LineString);

        $lineString->addPoints(... $points);

        return self::withBoundingBox($lineString, $data);
    }

    /**
     * @param array<int|string, mixed> $data
     */
    private static function parsePolygon(array $data): Polygon
    {
        return self::withBoundingBox(self::polygon(self::coordinates($data, GeoJsonType::Polygon)), $data);
    }

    /**
     * @param array<int|string, mixed> $data
     */
    private static function parseMultiLineString(array $data): MultiLineString
    {
        $multiLineString = Factory::make(GeoJsonType::MultiLineString);

        $lines = self::coordinates($data, GeoJsonType::MultiLineString);

        self::assertNestedCoordinates($lines, GeoJsonType::MultiLineString);

        foreach ($lines as $line) {
            $lineString = Factory::make(GeoJsonType::LineString);
            $lineString->addPoints(... self::points($line, GeoJsonType::MultiLineString));

            $multiLineString->setLineStrings($lineString);
        }

        return self::withBoundingBox($multiLineString, $data);
    }

    /**
     * @param array<int|string, mixed> $data
     */
    private static function parseMultiPolygon(array $data): MultiPolygon
    {
        $multiPolygon = Factory::make(GeoJsonType::MultiPolygon);

        $polygons = self::coordinates($data, GeoJsonType::MultiPolygon);

        self::assertNestedCoordinates($polygons, GeoJsonType::MultiPolygon);

        foreach ($polygons as $polygonCoordinates) {
            $multiPolygon->setPolygons(self::polygon($polygonCoordinates));
        }

        return self::withBoundingBox($multiPolygon, $data);
    }

    /**
     * @param array<int|string, mixed> $data
     */
    private static function parseGeometryCollection(array $data): GeometryCollection
    {
        $collection = Factory::make(GeoJsonType::GeometryCollection);

        $geometries = $data['geometries'] ?? null;

        if (!is_array($geometries)) {
            throw new ParseException(ParseExceptionType::InvalidGeometries);
        }

        foreach ($geometries as $geometry) {
            if (!is_array($geometry)) {
                throw new ParseException(ParseExceptionType::InvalidGeometries);
            }

            $collection->addGeometry(self::fromArray($geometry));
        }

        return self::withBoundingBox($collection, $data);
    }

    /**
     * @param array<int|string, mixed> $data
     */
    private static function parseFeature(array $data): Feature
    {
        $feature = Factory::make(GeoJsonType::Feature);

        if (array_key_exists('id', $data)) {
            $feature->setId(self::id($data['id']));
        }

        $feature->setGeometry(self::geometry($data));

        $properties = $data['properties'] ?? null;

        if ($properties !== null) {
            $feature->setProperties(self::properties($properties));
        }

        return self::withBoundingBox($feature, $data);
    }

    /**
     * @param array<int|string, mixed> $data
     */
    private static function parseFeatureCollection(array $data): FeatureCollection
    {
        $collection = Factory::make(GeoJsonType::FeatureCollection);

        $features = $data['features'] ?? null;

        if (!is_array($features)) {
            throw new ParseException(ParseExceptionType::InvalidFeatures);
        }

        foreach ($features as $feature) {
            $collection->addFeature(self::feature($feature));
        }

        return self::withBoundingBox($collection, $data);
    }

    /**
     * @param array<int|string, mixed> $coordinates
     *
     * @return array<int, Point>
     */
    private static function points(array $coordinates, GeoJsonType $type): array
    {
        $points = [];

        foreach ($coordinates as $coordinate) {
            if (!is_array($coordinate)) {
                throw new ParseException(ParseExceptionType::InvalidCoordinates, $type->value);
            }

            $points[] = self::position($coordinate);
        }

        return $points;
    }

    /**
     * @param array<int|string, mixed> $coordinates
     */
    private static function assertNestedCoordinates(array $coordinates, GeoJsonType $type): void
    {
        if (count($coordinates) === 0) {
            throw new ParseException(ParseExceptionType::InvalidCoordinates, $type->value);
        }

        foreach ($coordinates as $coordinatesSet) {
            if (!is_array($coordinatesSet)) {
                throw new ParseException(ParseExceptionType::InvalidCoordinates, $type->value);
            }
        }
    }

    /**
     * @param array<int|string, mixed> $coordinates
     */
    private static function polygon(array $coordinates): Polygon
    {
        $polygon = Factory::make(GeoJsonType::Polygon);

        self::assertNestedCoordinates($coordinates, GeoJsonType::Polygon);

        $polygon->setExteriorRing(... self::points($coordinates[0], GeoJsonType::Polygon));

        foreach (array_slice($coordinates, 1) as $ring) {
            $polygon->addInteriorRing(... self::points($ring, GeoJsonType::Polygon));
        }

        return $polygon;
    }

    private static function feature(mixed $data): Feature
    {
        if (!is_array($data)) {
            throw new ParseException(ParseExceptionType::InvalidFeatures);
        }

        $feature = self::fromArray($data);

        if (!$feature instanceof Feature) {
            throw new ParseException(ParseExceptionType::InvalidFeatures);
        }

        return $feature;
    }

    /**
     * @param array<int|string, mixed> $position
     */
    private static function position(array $position): Point
    {
        self::assertPosition($position);

        $point = Factory::make(GeoJsonType::Point);
        $point->setPoints($position[0], $position[1]);

        if (isset($position[2])) {
            $point->setAltitude($position[2]);
        }

        return $point;
    }

    /**
     * @param array<int|string, mixed> $position
     */
    private static function assertPosition(array $position): void
    {
        if (count($position) < 2) {
            throw new ParseException(ParseExceptionType::InvalidPosition, (string) count($position));
        }

        foreach ($position as $value) {
            if (!is_int($value) && !is_float($value)) {
                throw new ParseException(ParseExceptionType::InvalidCoordinate, get_debug_type($value));
            }
        }
    }

    /**
     * @param array<int|string, mixed> $data
     *
     * @return array<int|string, mixed>
     */
    private static function coordinates(array $data, GeoJsonType $type): array
    {
        $coordinates = $data['coordinates'] ?? null;

        if (!is_array($coordinates)) {
            throw new ParseException(ParseExceptionType::InvalidCoordinates, $type->value);
        }

        return $coordinates;
    }

    /**
     * @param array<int|string, mixed> $data
     */
    private static function isPosition(array $data): bool
    {
        return array_is_list($data) && count($data) >= 2;
    }

    /**
     * @param array<int|string, mixed> $data
     */
    private static function type(array $data): GeoJsonType
    {
        $type = $data['type'] ?? null;

        if (!is_string($type)) {
            throw new ParseException(ParseExceptionType::MissingType);
        }

        return GeoJsonType::tryFrom($type) ?? throw new ParseException(ParseExceptionType::UnknownType, $type);
    }

    private static function boundingBox(mixed $bbox): BoundingBox
    {
        if (!is_array($bbox) || count($bbox) !== 4 && count($bbox) !== 6) {
            throw new ParseException(ParseExceptionType::InvalidBoundingBox);
        }

        $boundingBox = Factory::make(GeoJsonType::BoundingBox);

        if (count($bbox) === 4) {
            $boundingBox->setPoints(self::position([$bbox[0], $bbox[1]]), self::position([$bbox[2], $bbox[3]]));
        } else {
            $boundingBox->setPoints(self::position([$bbox[0], $bbox[1]]), self::position([$bbox[3], $bbox[4]]));
            $boundingBox->setAltitudes($bbox[2], $bbox[5]);
        }

        return $boundingBox;
    }

    /**
     * @param array<int|string, mixed> $data
     */
    private static function withBoundingBox(GeoJsonObject $object, array $data): GeoJsonObject
    {
        if (!array_key_exists('bbox', $data)) {
            return $object;
        }

        $object->setBoundingBox(self::boundingBox($data['bbox']));

        return $object;
    }

    private static function id(mixed $id): string | int
    {
        if (!is_string($id) && !is_int($id)) {
            throw new ParseException(ParseExceptionType::InvalidId, get_debug_type($id));
        }

        return $id;
    }

    /**
     * @return array<string, mixed>
     */
    private static function properties(mixed $properties): array
    {
        if (!is_array($properties)) {
            throw new ParseException(ParseExceptionType::InvalidProperties, get_debug_type($properties));
        }

        return $properties;
    }

    /**
     * @param array<int|string, mixed> $data
     */
    private static function geometry(array $data): ?GeoJsonObjectInterface
    {
        $geometry = $data['geometry'] ?? null;

        if ($geometry === null) {
            return null;
        }

        if (!is_array($geometry)) {
            throw new ParseException(ParseExceptionType::InvalidGeometry, get_debug_type($geometry));
        }

        return self::fromArray($geometry);
    }
}
