<?php

declare(strict_types=1);

namespace RonAppleton\GeoJson\Enums;

enum BoundingBoxExceptionType: string
{
    case PointsNotSet = 'The bounding box\'s Points are not set.';
    case PointsSet = 'The bounding box\'s Points are already set.';
    case AltitudesSet = 'The bounding box\'s altitudes are already set.';
    case AltitudesNotSet = 'The bounding box\'s altitudes are not set.';
}
