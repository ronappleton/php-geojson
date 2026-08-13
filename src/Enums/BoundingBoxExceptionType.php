<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Enums;

enum BoundingBoxExceptionType: string
{
    case PointsNotSet = 'The bounding box points are not set.';
    case PointsSet = 'The bounding box points are already set.';
    case AltitudesNotSet = 'The bounding box altitudes are not set.';
    case AltitudesSet = 'The bounding box altitudes are already set.';
    case AlreadySet = 'The bounding box is already set on this object.';
    case InvalidOrder = 'The southwest corner must not be greater than the northeast corner.';
    case InvalidAltitudeOrder = 'The minimum altitude must not be greater than the maximum altitude.';
}
