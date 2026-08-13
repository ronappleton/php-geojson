<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Enums;

enum ParseExceptionType: string
{
    case NotAnObject = 'GeoJSON must be a JSON object, %s given.';
    case MissingType = 'A GeoJSON object must have a "type" member.';
    case UnknownType = 'Unknown GeoJSON type "%s".';
    case InvalidCoordinates = 'The "coordinates" member of a %s must be an array.';
    case InvalidPosition = 'A position must be an array of at least two numbers, %d element(s) given.';
    case InvalidCoordinate = 'Position coordinates must be numbers, %s given.';
    case InvalidId = 'The "id" member of a Feature must be a string or a number, %s given.';
    case InvalidProperties = 'The "properties" member of a Feature must be an object or null, %s given.';
    case InvalidGeometry = 'The "geometry" member of a Feature must be a GeoJSON object or null, %s given.';
    case InvalidGeometries = 'The "geometries" member of a GeometryCollection must be an array.';
    case InvalidFeatures = 'The "features" member of a FeatureCollection must be an array.';
    case InvalidBoundingBox = 'The "bbox" member must be an array of four or six numbers.';
}
