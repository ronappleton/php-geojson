<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Enums;

enum FeatureExceptionType: string
{
    case IdSet = 'The id is already set on this Feature.';
    case IdNotSet = 'The id is not set on this Feature.';
    case GeometrySet = 'The geometry is already set on this Feature.';
    case GeometryNotSet = 'The geometry is not set on this Feature.';
    case BoundingBoxSet = 'The bounding box is already set.';
    case BoundingBoxNotSet = 'The bounding box is not set on this Feature.';
    case PropertySet = 'The %s property is already set.';
    case PropertyNotSet = 'The %s property is not set.';
    case PropertiesNotSet = 'No properties are set on this Feature.';
}
