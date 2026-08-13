<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Enums;

use function array_map;
use function in_array;

enum GeoJsonType: string
{
    case Point = 'Point';
    case MultiPoint = 'MultiPoint';
    case LineString = 'LineString';
    case MultiLineString = 'MultiLineString';
    case Polygon = 'Polygon';
    case MultiPolygon = 'MultiPolygon';
    case GeometryCollection = 'GeometryCollection';
    case Feature = 'Feature';
    case FeatureCollection = 'FeatureCollection';
    case BoundingBox = 'bbox';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(static fn (self $case) => $case->value, self::cases());
    }

    /**
     * @return array<int, self>
     */
    public static function geometries(): array
    {
        return [
            self::Point,
            self::MultiPoint,
            self::LineString,
            self::MultiLineString,
            self::Polygon,
            self::MultiPolygon,
            self::GeometryCollection,
        ];
    }

    public function isGeometry(): bool
    {
        return in_array($this, self::geometries(), true);
    }
}
